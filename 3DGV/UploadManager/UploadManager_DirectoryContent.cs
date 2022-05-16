

using UnityEngine;
using System.Collections;
using SimpleFileBrowser;
using System.IO;
using System;
using System.Collections.Generic;
using UnityEngine.UI;
using System.Linq;
/*
    The Directory Content class.
    Contains all methods for retrieving 
    files in the specific directory
*/
/// <summary>
/// The Delete class.
/// Contains all methods for deleting
/// files in the genome system
/// </summary>
/// <remarks>
/// <para>Setup</para>
/// </remarks>
public class UploadManager_DirectoryContent : MonoBehaviour
{
    public bool batchUpload = false;
    //public string file_type;//Folder / Files

    [Header("Content type")]
    public ContentType contentType;
    [System.Serializable]
    public enum ContentType
    {
        Files,
        Folders
    }


    [Header("Section")]
    public string section;

    [Header("Folder / File")]

    public string[] extensions;

    public string targetpath;

    public GameObject container;

    [Header("Buttons")]
    public GameObject item_btn;

    public List<GameObject> itemsButtons_list;


    /*----------------------------------------------------------------------------------------------------*/

    void Start()
    {
        SetUploadSectionValue(section);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public void LoadDirectoryContent()
    {
        if (contentType == ContentType.Folders)
        {
            LoadDirectoryContent_Folders();
        }
        else
        if(contentType == ContentType.Files)
        {
            LoadDirectoryContent_Files();
        }
    }

    /*--------------------------------------------------*/

    public void LoadDirectoryContent_Folders()
    {
        itemsButtons_list.Clear();

        Dictionary<string, string> directoryContent = GetDirectories(targetpath);

        foreach (var attachStat in directoryContent)
        {
            string path_relative = UploadManager.Instance.GetRelativeDatabasePath(section) + attachStat.Key;
            string path_absolute = attachStat.Value;

            GameObject btn = Instantiate(item_btn, container.transform);
            btn.GetComponent<UploadManager_Button>().Setup(section, attachStat.Key, path_relative, path_absolute);
            btn.name = attachStat.Key + " (Button)";

            itemsButtons_list.Add(btn);
        }

    }

    public void LoadDirectoryContent_Files()
    {
        itemsButtons_list.Clear();

        Dictionary<string, string> directoryContent = GetFiles(targetpath);
        print("****" + targetpath);
        foreach (var attachStat in directoryContent)
        {
            string path_relative = Path.GetFileName(attachStat.Value);
            string path_absolute = attachStat.Value;

            GameObject btn = Instantiate(item_btn, container.transform);
            btn.GetComponent<UploadManager_Button>().Setup(section, attachStat.Key, path_relative, path_absolute);
            btn.name = attachStat.Key + " (Button)";

            itemsButtons_list.Add(btn);
        }
    }

    /*--------------------------------------------------*/

    private Dictionary<string, string> GetDirectories(string path)
    {
        print("*** " + section);
        print("*** " + path);
        DirectoryInfo directory = new DirectoryInfo(path);
        DirectoryInfo[] dirs = directory.GetDirectories();
        Dictionary<string, string> toreturn = new Dictionary<string, string>();

        foreach (DirectoryInfo sub in dirs)
        {
            toreturn.Add(sub.Name, sub.ToString());
        }

        return toreturn;
    }

    private Dictionary<string, string> GetFiles(string path)
    {
        print("GET FILES " + path);

        string[] extensions_tmp = extensions.Select(x => "*." + x).ToArray() ?? new[] { "*.*" };
        //print("extensions_tmp" + extensions_tmp[0] );

        DirectoryInfo directory = new DirectoryInfo(path);
        var files = extensions_tmp.SelectMany(directory.EnumerateFiles);
        Dictionary<string, string> toreturn = new Dictionary<string, string>();

        foreach (FileInfo file in files)
        {
            toreturn.Add(file.Name, file.ToString());
            print("extensions_tmp:" + file.Name);
        }

        return toreturn;
    }

    public void SetUploadSectionValue(string s)
    {
        string DatabasePath = DatabaseManager.Instance.GetDatabasePath();

        string Species = UploadManager.Instance.Data["Species"];
        string GenomeSet = UploadManager.Instance.Data["GenomeSet"];
        string CellType = UploadManager.Instance.Data["CellType"];
        string ModelSet = UploadManager.Instance.Data["ModelSet"];
        string Models = UploadManager.Instance.Data["Models"];
        string Annotations = UploadManager.Instance.Data["Annotations"];
        string Genes = UploadManager.Instance.Data["Genes"];

        switch (s)
        {
            case "Species":
                targetpath = DatabasePath;
            break;

            case "GenomeSet":
                targetpath = DatabasePath + "/" + Species;
            break;

            case "CellType":
                targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells";
            break;

            case "ModelSet":
                targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Models";
            break;

            case "Models":
                targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Models/" + ModelSet;
            break;

            case "Annotations":
                targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Annotations";
            break;

            case "Genes":
                targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Genes";
            break;
        }
    }

    public void SetSelectedButton(string selectedBtn)
    {
        for (int i=0; i< itemsButtons_list.Count; i++)
        {
            if (itemsButtons_list[i].GetComponent<UploadManager_Button>().value == selectedBtn)
            {
                //itemsButtons_list[i].GetComponent<Button>().interactable = false;
                itemsButtons_list[i].GetComponent<UploadManager_Button>().SelectItem();
            }
        }
    }

    //Clear item list
    public void ClearList()
    {
        foreach (Transform child in container.transform)
        {
            GameObject.Destroy(child.gameObject);
        }
    }

    /*--------------------------------------------------*/

    public void Add_btn()
    {
        if (contentType == ContentType.Folders)
        {
            AddFolder();
        }
        else
        if (contentType == ContentType.Files)
        {
            AddFile();
        }
    }

    /*--------------------------------------------------*/

    public void DeleteAll_btn()
    {
        UploadManager.Instance.UploadManager_DeleteAll.Setup(section, true);
    }

    /*--------------------------------------------------*/

    public void AddFolder()
    {
        UploadManager.Instance.UploadManager_CreateFolder.Setup(section, true);
        //UploadManager.Instance.UploadManager_CreateFolder.SetUploadSectionValue(section);
        //UploadManager.Instance.UploadManager_CreateFolder.gameObject.SetActive(true);
    }

    public void AddFile()
    {
        if(batchUpload){

            UploadManager.Instance.UploadManager_BatchUpload.SetFileExtension(extensions);
            UploadManager.Instance.UploadManager_BatchUpload.Setup(section, true);
        }
        else{
            UploadManager.Instance.UploadManager_UploadFile.SetFileExtension(extensions);
            UploadManager.Instance.UploadManager_UploadFile.Setup(section, true);
        }

        //UploadManager.Instance.UploadManager_UploadFile.SetUploadSectionValue(section);
        //UploadManager.Instance.UploadManager_UploadFile.gameObject.SetActive(true);
    }
}

//ShowDirectories();
//ShowFiles();

/*Dictionary<string, string> toreturn = new Dictionary<string, string>();

DirectoryInfo dir = new DirectoryInfo(path);
//FileInfo[] info = dir.GetFiles(extensions);
FileInfo[] info =
    dir.EnumerateFiles()
         .Where(f => extensions.Contains(f.Extension.ToLower()))
         .ToArray();

foreach (FileInfo f in info)
{
    toreturn.Add(f.Name, f.ToString());
}

return toreturn;*/


/*extensions = extensions.Select(x => "*." + x).ToArray() ?? new[] { "*.*" };
DirectoryInfo directory = new DirectoryInfo(path);
var files = extensions.SelectMany(directory.EnumerateFiles);
Dictionary<string, string> toreturn = new Dictionary<string, string>();

foreach (FileInfo file in files)
{
    toreturn.Add(file.Name, file.ToString());
}

return toreturn;*/

/*--------------------------------------------------*/

//public void ShowDirectories()
//{
//    Dictionary<string, string> toshow = GetDirectories(targetpath);
//    foreach (KeyValuePair<string, string> kv in toshow)
//    {
//        Debug.Log("Folder Key=" + kv.Key + ",Folder Value=" + kv.Value);
//    }
//}

//public void ShowFiles()
//{
//    Dictionary<string, string> toshow = GetFiles(targetpath, new[] { "bed", "csv" });
//    foreach (KeyValuePair<string, string> kv in toshow)
//    {
//        Debug.Log("File Key=" + kv.Key + ",File Value=" + kv.Value);
//    }
//}
//public void ShowDirectories()
//{
//    Dictionary<string, string> toshow = GetDirectories(targetpath);
//    foreach (KeyValuePair<string, string> kv in toshow)
//    {
//        Debug.Log("Folder Key=" + kv.Key + ",Folder Value=" + kv.Value);
//    }
//}

//public void ShowFiles()
//{
//    Dictionary<string, string> toshow = GetFiles(targetpath);
//    foreach (KeyValuePair<string, string> kv in toshow)
//    {
//        Debug.Log("File Key=" + kv.Key + ",File Value=" + kv.Value);
//    }
//}

/*--------------------------------------------------*/

//private static Dictionary<string, string> GetDirectories(string path)
//{
//    DirectoryInfo directory = new DirectoryInfo(path);
//    DirectoryInfo[] dirs = directory.GetDirectories();
//    Dictionary<string, string> toreturn = new Dictionary<string, string>();

//    foreach (DirectoryInfo sub in dirs)
//    {
//        toreturn.Add(sub.Name, sub.ToString());
//    }

//    return toreturn;
//}


//private static Dictionary<string, string> GetFiles(string path)
//{
//    DirectoryInfo directory = new DirectoryInfo(path);
//    FileInfo[] files = directory.GetFiles();
//    Dictionary<string, string> toreturn = new Dictionary<string, string>();

//    foreach (FileInfo file in files)
//    {
//        toreturn.Add(file.Name, file.ToString());
//    }

//    return toreturn;
//}



//}

//Now you can access the key and value both separately from this attachStat as:
//Debug.Log();
//Debug.Log();

//foreach(KeyValuePair<string, string> attachStat in directoryContent)
//for (int i = 0; i < directoryContent.Count; i++)
//{
//GameObject btn = Instantiate(item_btn);
//btn.transform.parent = container.transform;

//(Instantiate(item_btn) as GameObject).transform.parent = container.transform;
//GameObject particleObject = Instantiate(item_btn, container.transform.position, container.transform.rotation) as GameObject;