﻿using UnityEngine;
using System.Collections;
using SimpleFileBrowser;
using System.IO;
using System;
using System.Collections.Generic;
using UnityEngine.UI;

public class UploadManager_BatchUpload : MonoBehaviour
{
	[Header("Section")]
	public string section = "";

	[Header("Folder / File")]
	//public string TargetFolder;
	public string TargetFile;

	[Header("Genome Values")]

	//Database path (root)
	public string DatabasePath;
	public string RelativeSectionPath;

	//Destination file
	public string TargetFile_absolute;
	public string TargetFile_relative;

	//Destination folder
	public string TargetFolder_absolute;
	public string TargetFolder_relative;

	/*---*/

	//File source
	public static List<string> SourcePath_absolute = new List<string>();

	//File destination
	public string DestinationPath_absolute;
	public string DestinationPath_relative;

	[Header("UI References")]
	public Text heading;
	public InputField FeedbackMessage_InputField;
	public InputField TargetFile_InputField;
	public InputField TargetFolderRelative_InputField;
	public InputField TargetFolderAbsolute_InputField;
	public InputField DestinationPathRelative_InputField;
	public InputField DestinationPathAbsolute_InputField;

	[Header("Buttons")]
	public GameObject Browse_btn;
	public GameObject Submit_btn;
	public GameObject Completed_btn;

	[Header("File Browser")]
	bool FileSelected = false;


	[Header("Extension")]
	public string[] extensions;

	//[Header("Upload Files")]
	//public List<string> filenames = new List<string>();
	//string SourcePath_absolute = "";
	//string path_s = "";

	/*--------------------------------------------------*/

	void Start()
	{
		SetFileExtensionRestrictions();
	}

	void SetHeadingText()
	{
		heading.text = "Upload " + section;
	}

	void SetFileExtensionRestrictions()
	{
		FileBrowser.SetExcludedExtensions(".lnk", ".tmp", ".zip", ".rar", ".exe");
		FileBrowser.SetFilters(true, ".csv", ".bed");
		//FileBrowser.Set(true, ".csv", ".bed");
	}

	public void SetFileExtension(string[] ext)
	{
		extensions = ext;
	}

	/*--------------------------------------------------*/

	public void Setup(string s, bool e = false)
	{
		//Setup section
		section = s;

		//Set heading text
		SetHeadingText();

		//Reset
		ResetFields();

		//Update file system values
		UpdateFileSystemValues();

		//Update input field values
		UpdateInputFieldValues();

		//Display correct buttons
		DisplayButtonOptions();

		//Activate
		this.gameObject.SetActive(e);
	}

	void ResetFields()
	{
		//Feedback message
		FeedbackMessage_InputField.text = "";

		//Input fields
		TargetFile_InputField.text = "";
		TargetFolderRelative_InputField.text = "";
		TargetFolderAbsolute_InputField.text = "";
		DestinationPathRelative_InputField.text = "";
		DestinationPathAbsolute_InputField.text = "";

		//Variables
		DatabasePath = "";
		TargetFile = "";
		TargetFile_relative = "";
		TargetFile_absolute = "";
		TargetFolder_relative = "";
		TargetFolder_absolute = "";

		//Button
		Submit_btn.GetComponent<Button>().interactable = false;
	}

	public void UpdateFileSystemValues()
	{
		//Get database root paths
		DatabasePath = DatabaseManager.Instance.GetDatabasePath();
		RelativeSectionPath = UploadManager.Instance.GetRelativeDatabasePath(section);

		//Set database file values
		TargetFile = TargetFile_InputField.text;

		TargetFolder_relative = RelativeSectionPath;
		TargetFolder_absolute = DatabasePath + RelativeSectionPath;

		TargetFile_relative = RelativeSectionPath + TargetFile;
		TargetFile_absolute = DatabasePath + RelativeSectionPath + TargetFile;
	}

	public void UpdateInputFieldValues()
	{
		//Input fields
		TargetFile_InputField.text = "";
		TargetFolderRelative_InputField.text = TargetFolder_relative;
		TargetFolderAbsolute_InputField.text = TargetFolder_absolute;

		DestinationPathRelative_InputField.text = TargetFile_relative;
		DestinationPathAbsolute_InputField.text = TargetFile_absolute;
	}

	/*--------------------------------------------------
    Upload Folder Action
    --------------------------------------------------*/

	void LoadDirectoryContent()
	{
		UploadManager.Instance.SetEnabledSection(section);
	}

	/*--------------------------------------------------
    Feedback Message
    --------------------------------------------------*/

	private void FeedbackMessage(string txt, Color color)
	{
		//Set Color
		FeedbackMessage_InputField.textComponent.color = color;

		//Set Message
		FeedbackMessage_InputField.text = txt;
	}

	/*--------------------------------------------------
    Buttons Setup
    --------------------------------------------------*/

	void DisplayButtonOptions()
	{
		Browse_btn.SetActive(true);
		Submit_btn.SetActive(true);
		Completed_btn.SetActive(false);
	}

	void DisplayButtonComplete()
	{
		Browse_btn.SetActive(false);
		Submit_btn.SetActive(false);
		Completed_btn.SetActive(true);
	}

	/*--------------------------------------------------
    Buttons Actions
    --------------------------------------------------*/

	public void Submit()
	{
		if (FileSelected)
		{
			foreach(string f in SourcePath_absolute)
            {
				string path = TargetFolder_absolute + System.IO.Path.GetFileName(f);
                if (File.Exists(path))
                {
					FeedbackMessage("File existed", Color.red);
					LoadDirectoryContent();
					return;
				}

			}
			//if (File.Exists(DestinationPath_absolute))
			//{
			//	FeedbackMessage("File existed", Color.red);
			//}
			//else
			//if (Array.IndexOf(extensions, Path.GetExtension(DestinationPath_absolute).Substring(1)) < 0)
			////if (Array.IndexOf(extensions, DestinationPath_absolute.Substring(0, DestinationPath_absolute.LastIndexOf('.')) ) < 0)
			//{
			//	print("Submit " + extensions[0]);
			//	print("DestinationPath_absolute " + Path.GetExtension(DestinationPath_absolute));
			//	print("DestinationPath_absolute2 " + DestinationPath_absolute);
			//	print("DestinationPath_absolute3 " + DestinationPath_absolute.Substring(0, DestinationPath_absolute.LastIndexOf('.')));

			//	FeedbackMessage("File selected not compatible", Color.red);
			//}
			//else
			//{
				UploadFile();
				FeedbackMessage("File Copied", Color.green);
				DisplayButtonComplete();
			//}
		}
		else
		{
			FeedbackMessage("File not selected. Please click the browse button first", Color.red);
		}

		//Reload files
		LoadDirectoryContent();
	}

	void UploadFile()
	{
		foreach(string f in SourcePath_absolute) {
			System.IO.File.Copy(f, TargetFolder_absolute+ System.IO.Path.GetFileName(f));
		}
		FileSelected = false;
	}


	public void Browse()
	{
		StartCoroutine(ShowLoadDialogCoroutine());
	}

	IEnumerator ShowLoadDialogCoroutine()
	{
		yield return FileBrowser.WaitForLoadDialog(
			FileBrowser.PickMode.FilesAndFolders,
			true,
			TargetFolder_absolute,
			"Load File or Folder",
			"Load"
		);

		// Dialog is closed
		// Print whether the user has selected some files/folders or cancelled the operation (FileBrowser.Success)
		Debug.Log(FileBrowser.Success);

		//string filename_tmp = "";
		//string destinationPath_tmp = "";

		if (FileBrowser.Success)
		{
			// Print paths of the selected files (FileBrowser.Result) (null, if FileBrowser.Success is false)
			for (int i = 0; i < FileBrowser.Result.Length; i++)
			{
				//Get source file
				string sourceFile_tmp = FileBrowser.Result[i];
				//If directory
				if (FileBrowserHelpers.IsDirectory(sourceFile_tmp))
				{
					//DirectoryCopy(SourceFile, targetpath, true);//Copy whole directory
				}
				//If file
				else
				{
					//Filename
					TargetFile = System.IO.Path.GetFileName(sourceFile_tmp);
					TargetFile_InputField.text = TargetFile;

					//Full destination path
					SourcePath_absolute.Add(sourceFile_tmp);
                    //TargetFile_absolute = System.IO.Path.Combine(TargetFolder_absolute, TargetFile);
                    //TargetFile_relative = SourcePath_absolute;

                    //Target file (where it will be saved)
                    TargetFile_relative = TargetFolder_relative + TargetFile;
                    TargetFile_absolute = TargetFolder_absolute + TargetFile;
                    DestinationPath_absolute = TargetFile_absolute;

                    //Target folder
                    DestinationPathRelative_InputField.text = TargetFile_relative;
					DestinationPathAbsolute_InputField.text = TargetFile_absolute;

					//Enable submit button
					Submit_btn.GetComponent<Button>().interactable = true;

				}

			}

			FileSelected = true;
		}
	}
}