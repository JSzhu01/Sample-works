using UnityEngine;
using System.Collections;
using SimpleFileBrowser;
using System.IO;
using System;
using System.Collections.Generic;
using System.IO.Compression;
using UnityEngine.UI;

public class UploadManager_UploadFile : MonoBehaviour
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
	public string SourcePath_absolute;

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
		//FileBrowser.SetExcludedExtensions(".lnk", ".tmp", ".zip", ".rar", ".exe");
		FileBrowser.SetExcludedExtensions(".lnk", ".tmp", ".rar", ".exe");
		//FileBrowser.SetFilters(true, ".csv", ".bed");
		FileBrowser.SetFilters(true, ".zip");
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
			if (File.Exists(DestinationPath_absolute))
			{
				FeedbackMessage("File existed", Color.red);
			}
			else
            if (Array.IndexOf(extensions, Path.GetExtension(DestinationPath_absolute).Substring(1) ) < 0)
            //if (Array.IndexOf(extensions, DestinationPath_absolute.Substring(0, DestinationPath_absolute.LastIndexOf('.')) ) < 0)
            {
				print("Submit " + extensions[0] );
				print("DestinationPath_absolute " + Path.GetExtension(DestinationPath_absolute) );
				print("DestinationPath_absolute2 " + DestinationPath_absolute);
				print("DestinationPath_absolute3 " + DestinationPath_absolute.Substring(0, DestinationPath_absolute.LastIndexOf('.')) );

				FeedbackMessage("File selected not compatible", Color.red);
			}
			else
			{
				UploadFile();
				FeedbackMessage("Zip Copied", Color.green);
				DisplayButtonComplete();
			}
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
		//System.IO.File.Copy(SourcePath_absolute, DestinationPath_absolute);
		System.IO.File.Copy(SourcePath_absolute, DestinationPath_absolute);
		var intendedPath = string.Empty;
		var fileName = string.Empty;
		string tempfolder = TargetFolder_absolute + "temp";
		List<(string,string)> tobecopied = new List<(string,string)>();
		//open archive
		using (var archive = ZipFile.OpenRead(DestinationPath_absolute))
		{
			//since there is only one entry grab the first
			foreach (ZipArchiveEntry entry in archive.Entries){
				if (entry.Name == "") {
					fileName = entry.FullName;
					break;
				};
			     }
			//the relative path of the entry in the zip archive
			
			//intended path once extracted would be
			intendedPath = Path.Combine(tempfolder, fileName);
		}


		ZipFile.ExtractToDirectory(DestinationPath_absolute, tempfolder);
		Debug.Log("a:"+intendedPath);
		foreach(var file in Directory.EnumerateFiles(intendedPath))
        {
			if (Path.GetExtension(file) == ".bed")
			{
				string destFile = Path.Combine(TargetFolder_absolute, Path.GetFileName(file));
				tobecopied.Add((file,destFile));
			}
            else
            {
				Debug.Log(file + "format not correct!");
				tobecopied.Clear();
				break;
            }
			//Debug.Log("tobecopied"+destFile)
		}

		foreach (var filetuple in tobecopied)
        {
            if (!File.Exists(filetuple.Item2))
            {
				File.Move(filetuple.Item1, filetuple.Item2);
            }
        }

		//Directory.Delete(tempfolder, true);
		//File.Delete(DestinationPath_absolute);

		FileSelected = false;
	}


	public void Browse()
	{
		StartCoroutine(ShowLoadDialogCoroutine());
	}

	IEnumerator ShowLoadDialogCoroutine()
	{
		// Show a load file dialog and wait for a response from user
		// Load file/folder: file, Allow multiple selection: true
		// Initial path: default (Documents), Title: "Load File", submit button text: "Load"
		yield return FileBrowser.WaitForLoadDialog(
			FileBrowser.PickMode.FilesAndFolders,
			false,
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
					SourcePath_absolute = sourceFile_tmp;
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

					//Source File
					// = sourceFile_tmp;

					//Destination file
					//path_s = destinationPath_tmp;

					//Debug.Log(destfile);
					//filenames.Add(filename_tmp);???
					//System.IO.File.Copy(SourceFile, destinationPath_tmp, true);
				}

				//foreach (string files in filenames)//???
				//{
				//	//Debug.Log(files);
				//	//???
				//}
			}

			//Print(inputfield_0, targetpath, Color.black);
			//FeedbackMessage(targetpath, Color.black);
			//TargetFolderAbsolute_InputField.text = TargetFolder_absolute;
			//TargetFile_InputField.text = TargetFile;

			//DestinationPathRelative_InputField.text = TargetFile_relative + TargetFile;
			//DestinationPathAbsolute_InputField.text = destinationPath_tmp;

			FileSelected = true;
		}
	}
}


//targetpath = GetUploadDirectoryPath(s);

/*--------------------------------------------------*/


//public string GetUploadDirectoryPath(string s)
//{
//	string DatabasePath = DatabaseManager.Instance.GetDatabasePath();

//	//Directory path temp
//	string DatabasePath_tmp = DatabasePath + "/" + UploadManager.Instance.GetRelativeDatabasePath(s);

//	//Get target path
//	return DatabasePath_tmp;
//}



//string targetpath;

//SetUploadSectionValue("Species");
//Print(inputfield_0, targetpath, Color.black);
//FeedbackMessage(targetpath, Color.black);
//TargetFolderAbsolute_InputField.GetComponent<InputField>().text = targetpath;

//private void OnEnable()
//   {
//	SetHeadingText();
//}
//private static void Print(GameObject input, string texttoshow, Color color)
//{
//	Text temp = input.GetComponent<InputField>().textComponent;
//	temp.resizeTextForBestFit = true;
//	temp.color = color;
//	input.GetComponent<InputField>().text = texttoshow;
//}


/*
private static void DirectoryCopy(string source, string destination, bool copysub)
{
	DirectoryInfo directory = new DirectoryInfo(source);
	DirectoryInfo[] dirs = directory.GetDirectories();
	Directory.CreateDirectory(destination);
	FileInfo[] files = directory.GetFiles();
	foreach (FileInfo file in files)
	{
		string t_Path = Path.Combine(destination, file.Name);
		file.CopyTo(t_Path, true);
		try
		{
			Debug.Log(destination);
		}
		catch (Exception e)
		{
			Debug.LogException(e);
		}
	}
	if (copysub)
	{
		foreach (DirectoryInfo sub in dirs)
		{
			string d_t_path = Path.Combine(destination, sub.Name);
			DirectoryCopy(sub.FullName, d_t_path, copysub);
		}
	}
}
*/
//public List<string> showfilenames()
//{
//	return filenames;
//}

//SourcePath_absolute = (string[])FileBrowser.Result.Clone();
//Transform container = gameObject.transform.parent.parent;
//GameObject inputfield_0 = container.Find("InputField (0)").gameObject;
//GameObject inputfield_1 = container.Find("InputField (1)").gameObject;
//GameObject inputfield_2 = container.Find("InputField (2)").gameObject;



// Warning: paths returned by FileBrowser dialogs do not contain a trailing '\' character
// Warning: FileBrowser can only show 1 dialog at a time

//public Dictionary<string, string> GetFolderPath()
//   {
//	Dictionary<string, string> folderPaths = new Dictionary<string, string>();

//	folderPaths.Add("Species");

//	return folderPaths;
//}
//private static void Upload()
//   {

//   }