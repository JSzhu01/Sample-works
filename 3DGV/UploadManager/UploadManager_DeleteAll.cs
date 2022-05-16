using UnityEngine;
using System.Collections;
using SimpleFileBrowser;
using System.IO;
using System;
using System.Collections.Generic;
using UnityEngine.UI;
/*
    The Delete class.
    Contains all methods for deleting
    files in the genome system
*/
/// <summary>
/// The Delete class.
/// Contains all methods for deleting
/// files in the genome system
/// </summary>
/// <remarks>
/// <para>Setup(string s, string pr, string pa, bool e = false)</para>
/// <para>ResetFields()</para>
/// <para>UpdateFileSystemValues(string pr, string pa)</para>
/// <para>UpdateInputFieldValues()</para>
/// <para>Delete(string path)</para>
/// <para>LoadDirectoryContent()</para>
/// <para>ClickNo_btn()</para>
/// <para>ClickYes_btn()</para>
/// <para>DisplayButtonOptions()</para>
/// <para>DisplayButtonComplete()</para>
/// <para>FeedbackMessage(string txt, Color color)</para>
/// </remarks>
public class UploadManager_DeleteAll : MonoBehaviour
{
    [Header("Section")]
    public string section;

    [Header("Genome Values")]
    public string DatabasePath;
    public string RelativeSectionPath;
    public string TargetFolder_absolute;
    public string TargetFolder_relative;

    [Header("UI References")]
    public InputField FeedbackMessage_InputField;
    public InputField TargetPathRelative_InputField;
    public InputField TargetPathAbsolute_InputField;

    [Header("Buttons")]
    public GameObject No_btn;
    public GameObject Yes_btn;
    public GameObject Completed_btn;

    /*----------------------------------------------------------------------------------------------------*/

    public void Setup(string s, bool e = false)
    {
        //Setup
        section = s;

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

    /*----------*/

    void ResetFields()
    {
        //Feedback message
        FeedbackMessage_InputField.text = "";

        //Input fields
        TargetPathRelative_InputField.text = "";
        TargetPathAbsolute_InputField.text = "";

        //Variables
        TargetFolder_relative = "";
        TargetFolder_absolute = "";
    }

    public void UpdateFileSystemValues()
    {
        //Get database root paths
        DatabasePath = DatabaseManager.Instance.GetDatabasePath();
        RelativeSectionPath = UploadManager.Instance.GetRelativeDatabasePath(section);

        TargetFolder_relative = RelativeSectionPath;// + "/" + TargetFolder;
        TargetFolder_absolute = DatabasePath + RelativeSectionPath;

    }

    public void UpdateInputFieldValues()
    {
        TargetPathRelative_InputField.text = TargetFolder_relative;
        TargetPathAbsolute_InputField.text = TargetFolder_absolute;
    }

    /*--------------------------------------------------
    Delete All Action
    --------------------------------------------------*/

    public void DeleteAll(string path)
    {
        if (Directory.Exists(path))
        {
            DirectoryInfo directory = new DirectoryInfo(path);
            foreach(FileInfo file in directory.GetFiles())
            {
                file.Delete();
            }

            foreach(DirectoryInfo dir in directory.GetDirectories())
            {
                dir.Delete(true);
            }

            FeedbackMessage("Current section is cleared.", Color.green);

            //Reload files
            LoadDirectoryContent();
        }
        else
        {
            FeedbackMessage("Not a valid path or directory, please retry", Color.red);
        }
    }

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
        No_btn.SetActive(true);
        Yes_btn.SetActive(true);
        Completed_btn.SetActive(false);
    }

    void DisplayButtonComplete()
    {
        No_btn.SetActive(false);
        Yes_btn.SetActive(false);
        Completed_btn.SetActive(true);
    }

    /*--------------------------------------------------
    Buttons Actions
    --------------------------------------------------*/

    public void ClickNo_btn()
    {
        this.gameObject.SetActive(false);
    }

    public void ClickYes_btn()
    {
        DeleteAll(TargetFolder_absolute);
        DisplayButtonComplete();
    }

}

