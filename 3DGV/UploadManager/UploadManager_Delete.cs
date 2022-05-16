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
public class UploadManager_Delete : MonoBehaviour
{
    [Header("Section")]
    public string section;

    [Header("Genome Values")]
    public string TargetPath_absolute;
    public string TargetPath_relative;

    [Header("UI References")]
    public InputField FeedbackMessage_InputField;
    public InputField TargetPathRelative_InputField;
    public InputField TargetPathAbsolute_InputField;

    [Header("Buttons")]
    public GameObject No_btn;
    public GameObject Yes_btn;
    public GameObject Completed_btn;

    /*----------------------------------------------------------------------------------------------------*/

    public void Setup(string s, string pr, string pa, bool e = false)
    {
        //Setup
        section = s;

        //Reset
        ResetFields();

        //Update file system values
        UpdateFileSystemValues(pr, pa);

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
        TargetPath_relative = "";
        TargetPath_absolute = "";
    }

    public void UpdateFileSystemValues(string pr, string pa)
    {
        TargetPath_relative = pr;
        TargetPath_absolute = pa;
    }

    public void UpdateInputFieldValues()
    {
        TargetPathRelative_InputField.text = TargetPath_relative;
        TargetPathAbsolute_InputField.text = TargetPath_absolute;
    }

    /*--------------------------------------------------
    Delete Folder Action
    --------------------------------------------------*/

    public void Delete(string path)
    {
        if (File.Exists(path))
        {
            File.Delete(path);

            FeedbackMessage("File Deleted", Color.green);

            //Reload files
            LoadDirectoryContent();
        }
        else
        if(Directory.Exists(path))
        {
            Directory.Delete(path, true);

            FeedbackMessage("Folder Deleted", Color.green);

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
        Delete(TargetPath_absolute);
        DisplayButtonComplete();
    }

}


//FeedbackMessage("", Color.black);

//TargetPath_absolute = pa;
//TargetPath_relative = pr;

//TargetFolder = GetUploadDirectoryPath(s);


//[Header("Folder / File")]
//public string TargetPath;

//public string TargetFile;

/*----------------------------------------------------------------------------------------------------*/
//string TargetFolder;
///FeedbackMessage_InputField.GetComponent<InputField>().textComponent.resizeTextForBestFit = true;

//public string GetUploadDirectoryPath(string s)
//{
//    string DatabasePath = DatabaseManager.Instance.GetDatabasePath();

//    //Directory path temp
//    string DatabasePath_tmp = DatabasePath + "/" + UploadManager.Instance.GetRelativeDatabasePath(s);

//    //Get target path
//    return DatabasePath_tmp;
//}



// Warning: paths returned by FileBrowser dialogs do not contain a trailing '\' character
// Warning: FileBrowser can only show 1 dialog at a time
/*
//Directory path temp
string DatabasePath_tmp = "";

//Database path
string DatabasePath = DatabaseManager.Instance.GetDatabasePath();

//Genome settings
string Species = UploadManager.Instance.Data["Species"];
string GenomeSet = UploadManager.Instance.Data["GenomeSet"];
string CellType = UploadManager.Instance.Data["CellType"];
string Models = UploadManager.Instance.Data["Models"];
string ModelSet = UploadManager.Instance.Data["ModelSet"];
string Annotations = UploadManager.Instance.Data["Annotations"];
string Genes = UploadManager.Instance.Data["Genes"];

switch (s)
{
    case "Species":
        DatabasePath_tmp = DatabasePath;
        break;

    case "GenomeSet":
        DatabasePath_tmp = DatabasePath + "/" + Species;
        break;

    case "CellType":
        DatabasePath_tmp = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells";
        break;

    case "ModelSet":
        DatabasePath_tmp = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Models";
        break;

    case "Models":
        DatabasePath_tmp = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Models/" + ModelSet;
        break;

    case "Annotations":
        DatabasePath_tmp = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Annotations";
        break;

    case "Genes":
        DatabasePath_tmp = DatabasePath + "/" + Species + "/" + GenomeSet + "/Genes";
        break;
}

return DatabasePath_tmp;*/

//private void Clear(GameObject input)
//{
//    input.GetComponent<InputField>().text = "";
//}

//List<string> filenames = new List<string>();
//bool fileselected = false;

//string sourcefile_s = "";
//string path_s = "";


//void OnEnable()
//{
//    GetUploadDirectoryPath(section);
//    FeedbackMessage(TargetFile, Color.black);
//}
//TargetFolder = DatabaseManager.Instance.GetDatabasePath();
//private static void Upload()
//   {

//   }

//public GameObject yes_button;
//public GameObject no_button;