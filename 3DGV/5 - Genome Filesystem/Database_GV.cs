using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.Threading.Tasks;
using Sirenix.OdinInspector;
using System.IO;
using System.Linq;

public class Database_GV : SerializedMonoBehaviour
{
    [Header("Genome Manager")]
    public GenomeManager_GV GenomeManager;

    [Header("FileSystem")]
    public FileSystem_GV FileSystem;

    [Header("Database Folder")]
    public string DatabaseFolder;

    //--------------------------------------------------//

    [ShowInInspector]
    Dictionary<string, string> GenomeSettings = new Dictionary<string, string>() {
        { "Species", "" },
        { "GenomeSet", "" },
        { "CellType", "" },
        { "ModelSet", "" },
        { "Chromosomes", "" }
    };

    [ShowInInspector]
    Dictionary<string, string> GenomeSelection = new Dictionary<string, string>() {
        { "Species", "" },
        { "GenomeSet", "" },
        { "CellType", "" },
        { "ModelSet", "" },
        { "Chromosomes", "" },
        { "Annotations", "" },
        { "Genes", "" }
    };


    //----------------------------------------------------------------------------------------------------//

    void Awake()
    {
        SetDatabaseFolder();
    }

    // Start is called before the first frame update
    void Start()
    {
        //GetFilesTest();
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    //----------------------------------------------------------------------------------------------------//

    void SetDatabaseFolder()
    {
        string rootFolder = Application.persistentDataPath;
        string databaseFolder = "Genome Database";
        string fullFolder = rootFolder + "/" + databaseFolder;

        DatabaseFolder = fullFolder;
    }

    //----------------------------------------------------------------------------------------------------//

    public bool EnableGetFiles = true;
    public Dictionary<string, string> GetDatabaseItems(Dictionary<string, string> genomeSelection, string section)
    {
        string genomeSelectionPath = GetSectionPath(genomeSelection, section);
        print("GetDatabaseItems : genomeSelectionPath  : " + genomeSelectionPath);
        Dictionary<string, string> directoryItems = new Dictionary<string, string>();



            if (section == "Chromosome")
            {
                print("GetDatabaseItems : Chromosome");
                //if (EnableGetFiles)
                //{
                    directoryItems = FileSystem.GetDirectoryFiles(genomeSelectionPath);
                //}
            }
            else
            {

                print("GetDatabaseItems : Non Chromosome");
                //if (EnableGetFiles)
                //{
                    directoryItems = FileSystem.GetDirectoryFolders(genomeSelectionPath);
                //}
            }


        //DatabaseFolder\
        return directoryItems;
    }

    string GetSectionPath(Dictionary<string, string> settings, string section, bool rootFolderPathEnabled = true)
    {
        string sectionPath = "null";

        switch (section)
        {
            case "Species":
                sectionPath = "/";
            break;

            case "GenomeSet":
                sectionPath = "/" + settings["Species"] + "/";
            break;

            case "CellType":
                sectionPath = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Cells/";
            break;

            case "ModelSet":
                sectionPath = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Cells/" + settings["CellType"] + "/Models/";
            break;

            case "Chromosome":
                sectionPath = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Cells/" + settings["CellType"] + "/Models/" + settings["ModelSet"] + "/";
            break;

            case "Annotations":
                sectionPath = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Cells/" + settings["CellType"] + "/Annotations/";
            break;

            case "Genes":
                sectionPath = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Genes/";
            break;
        }

        if (rootFolderPathEnabled) {
            sectionPath = DatabaseFolder + sectionPath;
        }
        
        return sectionPath;
    }

    void GetDatabaseFiles(string section)
    {
        Dictionary<string, string> files = FileSystem.GetDirectoryFolders(DatabaseFolder);

        foreach (KeyValuePair<string, string> entry in files)
        {
            print("Value " + entry.Value);
            print("Key " + entry.Key);
        }
    }

    //void GetFilesTest()
    //{
    //    GetDatabaseFiles("Species");
    //}

    string GetDatabaseKey()
    {
        return "";
    }

    public string GetDatabaseFileText(string file)
    {
        System.Threading.Thread.CurrentThread.CurrentCulture = new System.Globalization.CultureInfo("en-US");
        System.Threading.Thread.CurrentThread.CurrentUICulture = new System.Globalization.CultureInfo("en-US");

        string fileText = FileSystem.GetFileText(file);

        return fileText;
    }





    //Reads file and saves to model database
    public async Task LoadModelAsync(string file)
    {
        /*
        string fileText = FileSystem.GetFileText(file);

        print(fileText);

        //// Check if data is being loaded
        //if (CurrentProcesses.Contains(file) || modelData.ContainsKey(file))
        //{
        //    Debug.Log("Current processes already contain file: " + file);
        //    return;
        //}
        //else
        //{
        //    Debug.Log("Current processes doesnt already contain file: " + file);
        //}

        //LogSystem.Log("<b>GetModel() CurrentProcesses Add : </b> " + file);
        //CurrentProcesses.Add(file);
        //LogSystem.Log("<b>GetModel() CurrentProcesses Added </b> ");

        //--------------------------------------------------//

        //LogSystem.Log("<b>GetModel() Read Data START </b> ");

        // If not then Load the data
        //string data = FileSystem.GetFile("LoadModelAsync");

        //ANTOINE DB
        //using (StreamReader reader = new StreamReader(file))//  File.OpenText(file))
        //{
        //    //LogSystem.Log("<b>GetModel() Read Data MIDDLE </b> ");

        //    data = await reader.ReadToEndAsync();
        //}

        //LogSystem.Log("<b>GetModel() Read Data END </b> " + data.Length);

        //--------------------------------------------------//

        Model_GV model;

        if (data != null)
        {
            //LogSystem.Log("<b>GetModel() Data Null 1</b> ");

            // Create model and parse the 3D points
            string sourceFile = file;
            //if (GenomeManager.Instance.Player.mode != Player.Mode.Ar || CenteringTest == true)
            //{
            //    TargetMarker = GenomeManager.Instance.CenterTargetObject.transform.position;
            //}
            //else
            //{
            //    TargetMarker = GenomeManager.Instance.GenomeOffset;// GenomeManager.Instance.CenterTargetObject.transform.position;
            //}
            //LogSystem.Instance.Log("<b>[AR - TargetMarker]</b>" + TargetMarker);

            //LogSystem.Log("<b>GetModel() A</b> ");

            List<DataPoint_GV> points = await Task.Run(() => ParseModel(data, file));//CMOS777
                                                                                     //List<GV_DataPoint> points = ParseModel(data, file);
            //LogSystem.Log("<b>GetModel() B</b> ");

            model = new GV_Model(sourceFile, points);

            //LogSystem.Log("<b>GetModel() C</b> ");


            // Add to database
            modelData.Add(file, model);
        }
        //File not found
        else
        {
            //LogSystem.Log("<b>GetModel() Data is Null</b> ");

            Debug.LogError(string.Format("File path {0} not found.", file));
        }

        //Remove process from queue
        //LogSystem.Log("<b>GetModel() CurrentProcesses Remove : </b> " + file);
        //CurrentProcesses.Remove(file);
        //LogSystem.Log("<b>GetModel() CurrentProcesses Remove </b> ");
        */
    }

    public string GetGeneFile(Dictionary<string, string> settings, bool rootFolderPathEnabled = true)
    {
        string geneFile = "/Users/chrisdrogaris/Library/Application Support/McGill/3DGV - 3D Genome Viewer/Genome Database/Human/GRCh38/Genes/hg38_refGene/chr1.bed";


        geneFile = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Genes/";

        if (rootFolderPathEnabled)
        {
            geneFile = DatabaseFolder + geneFile;
        }

        Dictionary<string,string> geneFolder = FileSystem.GetDirectoryFolders(geneFile);

        geneFile = geneFolder[geneFolder.First().Key];

        string chromosome = settings["Chromosome"];
        string[] chromosomeArr = chromosome.Split('.');
        chromosome = "chr" + chromosomeArr[0];

        geneFile += "/" + chromosome + ".bed";



        print("GENEFILE " + geneFile);
        return geneFile;
    }

    public string GetGeneDiseaseSetFile(Dictionary<string, string> settings, bool rootFolderPathEnabled = true)
    {
        string geneFile = "/Users/chrisdrogaris/Library/Application Support/McGill/3DGV - 3D Genome Viewer/Genome Database/Human/GRCh38/Genes/hg38_refGene/chr1.bed";


        geneFile = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Genes/";

        if (rootFolderPathEnabled)
        {
            geneFile = DatabaseFolder + geneFile;
        }

        Dictionary<string, string> geneFolder = FileSystem.GetDirectoryFolders(geneFile);

        geneFile = geneFolder[geneFolder.First().Key];

        string chromosome = settings["Chromosome"];
        string[] chromosomeArr = chromosome.Split('.');
        chromosome = "chr" + chromosomeArr[0];

        geneFile += "/_DiseaseSearchChromosomePairs/" + chromosome + ".csv";



        print("GENEFILE " + geneFile);
        return geneFile;
    }

    public string GetGeneFileFolder(Dictionary<string, string> settings, bool rootFolderPathEnabled = true)
    {
        string geneFile = "/Users/chrisdrogaris/Library/Application Support/McGill/3DGV - 3D Genome Viewer/Genome Database/Human/GRCh38/Genes/hg38_refGene/chr1.bed";


        geneFile = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Genes/";

        if (rootFolderPathEnabled)
        {
            geneFile = DatabaseFolder + geneFile;
        }

        //Dictionary<string, string> geneFolder = FileSystem.GetDirectoryFolders(geneFile);

        //geneFile = geneFolder[geneFolder.First().Key];

        //string chromosome = settings["Chromosome"];
        //string[] chromosomeArr = chromosome.Split('.');
        //chromosome = "chr" + chromosomeArr[0];

        //geneFile += "/" + chromosome + ".bed";



        print("GENEFILE " + geneFile);
        return geneFile;
    }
    //----------------------------------------------------------------------------------------------------//

    public List<string> GetAllChromosomes(Dictionary<string, string> settings, bool rootFolderPathEnabled = true)//string order
    {
        //Dictionary<string, string> settings = GenomeManager.GenomeSettings;

        //Get current folder path of current chromosome
        List<string> listAll = new List<string>();

        string chromosomePath = "/" + settings["Species"] + "/" + settings["GenomeSet"] + "/Cells/" + settings["CellType"] + "/Models/" + settings["ModelSet"] + "/";

        if (rootFolderPathEnabled)
        {
            chromosomePath = DatabaseFolder + chromosomePath;
        }

        //----------//

        Dictionary<string, string> files = FileSystem.GetDirectoryFiles(chromosomePath);
        foreach (KeyValuePair<string, string> entry in files)
        {
            //print("Value " + entry.Value);
            //print("Key " + entry.Key);
            string filename = entry.Key;// Path.GetFileNameWithoutExtension();
            listAll.Add(filename);
        }

        return listAll;
    }

}


//Dictionary<string, string> settings = GenomeManager.GenomeSettings;

//////Get folder + 
//foreach (string file in Directory.GetFiles(geneFile, "*.bed")) //for every bed file inside the h38_refgene (already divided into chromosome)
//{
//    foreach (string word in splits) //for every model chromosome
//    {
//        if (word.Equals(Path.GetFileNameWithoutExtension(file))) //checking if the gene chromosome matches model chromosome, and associates if so. 
//        {
//            //AssociateGene(file, model);
//            return file;
//        }
//    }
//}