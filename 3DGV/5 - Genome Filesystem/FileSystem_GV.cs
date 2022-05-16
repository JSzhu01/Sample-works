using UnityEngine;
using System.Collections;
using SimpleFileBrowser;
using System.IO;
using System;
using System.Collections.Generic;
using UnityEngine.UI;
using System.Linq;
using System.Threading.Tasks;

//Get files from path
//Save files on path
//Delete files on path
public class FileSystem_GV : MonoBehaviour
{
    public string[] Extensions = new string[] { "*.csv", "*.bed" };

    public bool EnableGetFoldersFiles = true;

    //--------------------------------------------------//

    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    //--------------------------------------------------//

    public Dictionary<string, string> GetDirectoryFiles(string path)
    {
        print("GET FILES " + path);

        string[] extensions_tmp = Extensions.Select(x => "*." + x).ToArray() ?? new[] { "*.*" };
        //print("extensions_tmp" + extensions_tmp[0] );

        DirectoryInfo directory = new DirectoryInfo(path);

        //var sorted = Directory.GetFiles(".").OrderBy(f => f);

        var files = extensions_tmp.SelectMany(directory.EnumerateFiles);
        Dictionary<string, string> toreturn = new Dictionary<string, string>();

        //print("GET FILES COUNT " + files.Length);


        foreach (FileInfo file in files)
            {
                if (EnableGetFoldersFiles)
                {
                    toreturn.Add(file.Name, file.ToString());
                }
                print("extensions_tmp:" + file.Name);
            }


        return toreturn;
    }




    public Dictionary<string, string> GetDirectoryFolders(string path)
    {
        print("GetDirectoryFolders");
        Dictionary<string, string> directoryFolders = new Dictionary<string, string>();


            //Directory folder / settings
            DirectoryInfo directoryInfo = new DirectoryInfo(path);
            DirectoryInfo[] directory = directoryInfo.GetDirectories();

        print("GetDirectoryFolders" + directory.Length);

        foreach (DirectoryInfo folder in directory)
            {

                    string folderName = folder.Name;
                    string folderPath = folder.ToString();
                if (EnableGetFoldersFiles)
                {
                    directoryFolders.Add(folderName, folderPath);
                }
                print("GetDirectoryFoldersA " + folderName);
                print("GetDirectoryFoldersB " + folderPath);

            }

        return directoryFolders;
    }

    //--------------------------------------------------//

    //public delegate void TestDelegate(); // This defines what type of method you're going to call.
    /*public delegate void GetFile_CB(string txt);

    public GetFile_CB m_methodToCall; // This is the variable holding the method you're going to call.

    public async Task GetFile(string file, GetFile_CB method)
    {
        string fileText = "";

        //ANTOINE DB
        using (StreamReader reader = new StreamReader(file))//  File.OpenText(file))
        {
            //LogSystem.Log("<b>GetModel() Read Data MIDDLE </b> ");

            fileText = await reader.ReadToEndAsync();
        }

        method(fileText);
        //return fileText;
    }*/

    public bool CheckFileExists(string file)
    {
        bool fileExists = System.IO.File.Exists(file);

        return fileExists;
    }

    public string GetFileText(string file)
    {
        string fileText = "";

        StreamReader reader = new StreamReader(file);
        fileText = reader.ReadToEnd();
        reader.Close();

        return fileText;
    }


    public void SetUploadSectionValue(string s)
    {
        //string DatabasePath = DatabaseManager.Instance.GetDatabasePath();

        //string Species = UploadManager.Instance.Data["Species"];
        //string GenomeSet = UploadManager.Instance.Data["GenomeSet"];
        //string CellType = UploadManager.Instance.Data["CellType"];
        //string ModelSet = UploadManager.Instance.Data["ModelSet"];
        //string Models = UploadManager.Instance.Data["Models"];
        //string Annotations = UploadManager.Instance.Data["Annotations"];
        //string Genes = UploadManager.Instance.Data["Genes"];

        //switch (s)
        //{
        //    case "Species":
        //        targetpath = DatabasePath;
        //        break;

        //    case "GenomeSet":
        //        targetpath = DatabasePath + "/" + Species;
        //        break;

        //    case "CellType":
        //        targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells";
        //        break;

        //    case "ModelSet":
        //        targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Models";
        //        break;

        //    case "Models":
        //        targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Models/" + ModelSet;
        //        break;

        //    case "Annotations":
        //        targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Annotations";
        //        break;

        //    case "Genes":
        //        targetpath = DatabasePath + "/" + Species + "/" + GenomeSet + "/Genes";
        //        break;
        //}
    }
}




//public List<string> GetDirectoryFiles(string directory, string extension = "*.*")
//{
//    string[] extensions_tmp = Extensions.Select(x => "*." + x).ToArray() ?? new[] { "*.*" };

//    List<string> files = new List<string>();
//    Dictionary<string, string> filesDict = new Dictionary<string, string>();

//    //----------//

//    DirectoryInfo d = new DirectoryInfo(directory);

//    var filesa = extensions_tmp.SelectMany(d.EnumerateFiles);

//    foreach (FileInfo file in filesa)
//    {
//        filesDict.Add(file.Name, file.ToString());
//    }

//    return files;
//}


//public Dictionary<string, string> GetDirectoryFiles2(string path, string extension = "*.*")
//{
//    Dictionary<string, string> files = new Dictionary<string, string>();

//    FileInfo directoryInfo = new FileInfo(path);
//    string[] directory = Directory.GetFiles(path);

//    //foreach (FileInfo file in directory)
//    //{
//    //    files.Add(file.Name, file.ToString());
//    //}

//    return files;

//    ////print("GET FILES " + path);

//    //string[] extensions_tmp = Extensions.Select(x => "*." + x).ToArray() ?? new[] { "*.*" };
//    ////print("extensions_tmp" + extensions_tmp[0] );

//    //DirectoryInfo directory = new DirectoryInfo(d);
//    //var files = extensions_tmp.SelectMany(directory.EnumerateFiles);
//    //Dictionary<string, string> toreturn = new Dictionary<string, string>();

//    //foreach (FileInfo file in files)
//    //{
//    //    toreturn.Add(file.Name, file.ToString());
//    //    print("extensions_tmp:" + file.Name);
//    //}


//    //return toreturn;
//}