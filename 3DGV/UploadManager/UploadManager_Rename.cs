using UnityEngine;
using System.Collections;
using SimpleFileBrowser;
using System.IO;
using System;
using System.Collections.Generic;
using UnityEngine.UI;

public class UploadManager_Rename : MonoBehaviour
{
    [Header("Section")]
    public string section;

    [Header("Name")]
    public string TargetName;


    [Header("Genome Values")]
    public string DatabasePath;
    public string RelativeSectionPath;
    public string TargetPath_absolute;
    public string TargetPath_relative;
    public string TargetName_absolute;
    public string TargetName_relative;

    [Header("UI References")]
    public InputField FeedbackMessage_InputField;
    public InputField TargetFile_InputField;
    public InputField TargetPathRelative_InputField;
    public InputField TargetPathAbsolute_InputField;

    [Header("Buttons")]
    public GameObject Cancel_btn;
    public GameObject Submit_btn;
    public GameObject Completed_btn;

    /*--------------------------------------------------*/

    public void Setup(string s, string pr, string pa, bool e = false)
    {
        //Setup values
        section = s;

        //Reset
        ResetFields();

        //Update file system values
        UpdateFileSystemValues(pr,pa);

        //Update input field values
        UpdateInputFieldValues();

        //Display correct buttons
        DisplayButtonOptions();

        //Activate
        this.gameObject.SetActive(e);

        //Set input field focus
        SetInputFieldFocus();
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
        TargetPath_absolute = "";
        TargetPath_relative = "";

        //Button
        Submit_btn.GetComponent<Button>().interactable = false;
    }

    public void UpdateFileSystemValues(string pr, string pa)
    {
        //Root
        DatabasePath = DatabaseManager.Instance.GetDatabasePath();
        RelativeSectionPath = UploadManager.Instance.GetRelativeDatabasePath(section);

        TargetName = TargetFile_InputField.text;

        TargetPath_relative = pr;
        TargetPath_absolute = pa;

        TargetName_relative = RelativeSectionPath + TargetName;
        TargetName_absolute = DatabasePath + RelativeSectionPath + TargetName;
    }

    void UpdateName()
    {
        TargetName = TargetFile_InputField.text;
        TargetName_relative = RelativeSectionPath + TargetName;
        TargetName_absolute = DatabasePath + RelativeSectionPath + TargetName;
    }


    public void UpdateInputFieldValues()
    {
        TargetPathRelative_InputField.text = TargetPath_relative;
        TargetPathAbsolute_InputField.text = TargetPath_absolute;
    }

    /*--------------------------------------------------
    Rename Action
    --------------------------------------------------*/

    void Rename(string oldpath)
    {
        if (File.Exists(oldpath))
        {
            File.Move(oldpath,TargetName_absolute);

            FeedbackMessage("File Renamed", Color.green);

            //Reload files
            LoadDirectoryContent();
        }
        else
        if (Directory.Exists(oldpath))
        {
            Directory.Move(oldpath, TargetName_absolute);

            FeedbackMessage("Directory Renamed", Color.green);

            //Reload files
            LoadDirectoryContent();
        }
        else
        {
            FeedbackMessage("Not a valid path or directory, please retry", Color.red);
        }
    }

    /*--------------------------------------------------
    Feedback Message
    --------------------------------------------------*/

    void FeedbackMessage(string txt, Color color)
    {
        //Set
        FeedbackMessage_InputField.textComponent.color = color;

        //Set message
        FeedbackMessage_InputField.text = txt;
    }

    /*--------------------------------------------------
    Input Field Listener
    --------------------------------------------------*/

    public void RetrieveInputValues()
    {
        //Update file system values
        UpdateName();

        //Update input field values
        UpdateInputFieldValues();

        //Enable submit button
        EnableSubmitButton();
    }

    void EnableSubmitButton()
    {
        if (TargetName.Length > 0)
        {
            Submit_btn.GetComponent<Button>().interactable = true;
        }
        else
        {
            Submit_btn.GetComponent<Button>().interactable = false;
        }
    }

    void SetInputFieldFocus()
    {
        TargetFile_InputField.ActivateInputField();
        TargetFile_InputField.Select();
    }

    /*--------------------------------------------------
    Buttons Setup
    --------------------------------------------------*/

    void DisplayButtonOptions()
    {
        Cancel_btn.SetActive(true);
        Submit_btn.SetActive(true);
        Completed_btn.SetActive(false);
    }

    void DisplayButtonComplete()
    {
        Cancel_btn.SetActive(false);
        Submit_btn.SetActive(false);
        Completed_btn.SetActive(true);
    }

    /*--------------------------------------------------
    Buttons Actions
    --------------------------------------------------*/

    public void ClickCancel_btn()
    {
        ResetFields();
        this.gameObject.SetActive(false);
    }

    public void ClickSubmit_btn()
    {
        Rename(TargetPath_absolute);
        DisplayButtonComplete();
    }

    //Create auto generated folders on create new folder

    void LoadDirectoryContent()
    {
        UploadManager.Instance.SetEnabledSection(section);
    }
}
