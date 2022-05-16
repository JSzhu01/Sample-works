using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using Sirenix.OdinInspector;
using Sirenix.Serialization;
using System;
using System.Linq;

public class GenomeManager_GV : SerializedMonoBehaviour
{
    public GenomeMenu_Secction_Annotations_GV GenomeMenu_Secction_Annotations;

    public ConfidenceScore_GV ConfidenceScore;

    //----------//
    // Menu Type
    //----------//

    [Header("Genome - Menu Type (Mobile/VR)")]
    [SerializeField]
    public MenuType_enum MenuType;
    public enum MenuType_enum
    {
        Mobile,
        Vr
    };

    [Header("Genome - Menu (References)")]
    public GameObject MenuType_VR;
    public GameObject MenuType_Mobile;
    public MenuSystem_GV MenuSystem;

    //----------//
    // Player
    //----------//

    [Header("Player")]
    public Player_GV Player;

    [Header("Audio Manager")]
    public AudioManager_GV AudioManager;

    //----------//
    // Settings
    //----------//

    [Header("Genome - Settings")]
    public Settings_GV Settings;

    //-----//

    [Header("Genome - Scale")]
    [Range(1f, 100f)] public float GenomeScale = 1f;

    [Header("Genes - Auto Enable")]
    public string AutoEnableGene = "Off";
    public string AnnotationConfidenceMode = "Off";
    
    //----------//
    // Database
    //----------//

    [Header("Genome - Database")]
    public Database_GV Database;

    [Header("Genome - Disease Gene DB")]
    public DiseaseGeneDB_GV DiseaseGeneDB;

    //----------//
    // Database UI
    //----------//

    [Header("Genome - Menu UI")] //TODO set based on menu type
    public GenomeMenu_DataSelection_GV GenomeMenu_DataSelection;
    public GenomeMenu_Overview_GV GenomeMenu_Overview;
    public AnnotationVisuals_UI_GV AnnotationVisuals_UI;

    //--------------------//
    // Data Panel
    //--------------------//

    public DataPanelManager_GV DataPanel_Manager;
    public DataPanel_System_GV DataPanel_System;

    //--------------------//
    // Database Settings
    //--------------------//

    [Header("Genome Settings")]
    [ShowInInspector]
    public Dictionary<string, string> GenomeSettings = new Dictionary<string, string>() {
        { "Species", "" },
        { "GenomeSet", "" },
        { "CellType", "" },
        { "ModelSet", "" },
        { "Chromosome", "" },
        { "Genes", "Off" },
        { "Annotations", "" }
    };

    //----------//

    [Header("Genome Settings (Saved / Default)")]
    public bool AutoSetDefaultGenomeSettings = false;
    Dictionary<string, string> DefaultGenomeSettings = new Dictionary<string, string>() {
        { "Species", "Human" },
        { "GenomeSet", "GRCh38" },
        { "CellType", "Rao_HUVEC" },
        { "ModelSet", "MDS" },
        { "Chromosome", "1.csv" },
        { "Genes", "On" },
        { "Annotations", "" }
    };

    //--------------------//
    // Render Managers
    //--------------------//

    [Header("Genome - Render System")]
    public RenderSystem_GV RenderSystem;
    public ModelVisuals_GV ModelVisuals;
    public GeneVisuals_GV GeneVisuals;
    public AnnotationVisuals_GV AnnotationVisuals;

    //----------//

    [Header("Genome - Data Panel Manager")]
    public DataPanelManager_GV DataPanelManager;

    //------------------------------------------------------------//
    // Hover Mesh / Selection Mesh / Search Mesh (Gene, Position)
    //------------------------------------------------------------//

    [Header("HoverMeshManager - Reference")]
    public HoverMeshManager_GV HoverMeshManager;

    [Header("HoverMeshManager - SequenceSelectionWidth")]
    public int HoverSelectionWidth = 50;

    [Header("HoverMeshSelectedManager - Reference")]
    public HoverMeshSelectedManager_GV HoverMeshSelectedManager;

    //----------//
    // Modules
    //----------//
    public TutorialManager_GV TutoriaManager;

    [Header("Loading Manager")]
    public LoadingManager LoadingManager;

    [Header("Arrow System- (Module)")]
    public ArrowSystem_Manager ArrowSystem_Manager;
    public ArrowSystem_GV ArrowSystem;
    public GameObject ArrowSystem_Manager2;


    [Header("Placeholder Camera - (Unity Editor)")]
    public GameObject UnityEditorCamera;

    [Header("Placeholder Camera - (Unity Editor)")]
    public Model_LineRender_GV Model_LineRender;


    //----------//
    // AR Centering
    //----------//
    public GameObject CenterTargetObject;// = 1f;
    //public float GenomeScale = 1f;
    public float GenomeScale_DEFAULT = 5f;
    public float GenomeScale_AR = 0.1f;
    public Vector3 GenomeOffset;


    //----------------------------------------------------------------------------------------------------//

    void Awake()
    {
        //LoadSceneStartObjects(false);

        DisableUnityEditorCamera();

        LoadMenuType();

        SetPlayerMode();
    }

    // Start is called before the first frame update
    void Start()
    {
        if (Player.Mode == "Ar")
        {
            StartCoroutine(InitializeAr_enum());
        }
        else
        {
            Setup();
        }
    }

    // Update is called once per frame
    void Update()
    {
        //...//
    }

    //----------------------------------------------------------------------------------------------------//
    // AR Setup
    //----------------------------------------------------------------------------------------------------//


    IEnumerator InitializeAr_enum()
    {
        yield return new WaitForSeconds(1f);

        if (Player.Mode == "Ar")
        {
            //Wait for placeholder
            while (Player.ArObj.GetComponent<ARManager>().PlaceObjectsOnPlane.markerPlaced == false)
            {
                yield return null;
            }
        }

        yield return new WaitForSeconds(1f);

        //TODO maybe disable menu
        //Initialize();
        Setup();
    }

    //void Initialize()
    //{
    //    Setup();

    //    //Initialize settings
    //    //GV_SettingsManager.Initialize();
    //}

    //----------------------------------------------------------------------------------------------------//

    public List<GameObject> SceneStartObjects;

    void LoadSceneStartObjects(bool b)
    {
        for (int i=0; i<SceneStartObjects.Count; i++)
        {
            SceneStartObjects[i].SetActive(b);
        }
    }

    public void Setup()
    {
        //Enable settings
        Settings.Setup();
        ModelVisuals.Setup();
        GeneVisuals.Setup();
        AnnotationVisuals.Setup();
        HoverMeshManager.Setup();


        //----------//
        //LoadSceneStartObjects(true);

        //Load last bookmark state
        LoadLastBookmarkState();

        //Enable enviroment demo
        EnableEnvironmentDemo(EnvironmentDemoEnabled);
    }

    //----------------------------------------------------------------------------------------------------//

    void SetPlayerMode()
    {
        string experience = PlayerPrefs.GetString("experience");

        Player.Setup(experience);
    }

    void SetArrowSystem()
    {
        //ArrowSystem_Manager.SetCamera(Player.Camera.GetComponent<Camera>());
        //ArrowSystem_Manager.Setup(ModelVisuals.CurrentModel);

        Camera cam = Player.Camera.GetComponent<Camera>();
        ArrowSystem.Setup(cam);
        ArrowSystem.gameObject.GetComponent<Canvas>().worldCamera = cam;// Player.Camera.GetComponent<Camera>();
        ArrowSystem.ArrowElement.GetComponent<ArrowElement_GV>().MainCamera = cam;// Player.Camera.GetComponent<Camera>();

        bool offsetEnabled = true;
        if (Player.Mode == "Ar")
        {
            offsetEnabled = true;
        }

        ArrowSystem.ArrowElement.GetComponent<ArrowElement_GV>().Setup(ModelVisuals.CurrentModel, offsetEnabled);
    }

    //----------------------------------------------------------------------------------------------------//
    // Debug / Unity Editor Tests
    //----------------------------------------------------------------------------------------------------//

    #region "Debug"

    void DisableUnityEditorCamera()
    {
        UnityEditorCamera.SetActive(false);
    }

    [Header("Environment Demo")]
    public bool EnvironmentDemoEnabled = false;
    public GameObject EnviromentDemo;

    public void EnableEnvironmentDemo(bool b)
    {
        EnviromentDemo.SetActive(b);
    }

    void EnableModel_LineRender()
    {
        string modelFileCSV = GetChromosomeFile();
        Model_LineRender.Setup(modelFileCSV);
    }

    #endregion

    //----------------------------------------------------------------------------------------------------//

    public void LoadGenomeSettings(Dictionary<string, string> genomeSettings)
    {
        DeleteGenomeRenders();
        SetFullGenomeSelection(genomeSettings);
        EnableModel();
    }

    //----------------------------------------------------------------------------------------------------//

    void LoadMenuType()
    {
        if (MenuType == MenuType_enum.Mobile)
        {
            MenuType_Mobile.SetActive(true);
            MenuType_VR.SetActive(false);
            MenuSystem = MenuType_Mobile.GetComponent<MenuSystem_GV>();
        }
        else
        if (MenuType == MenuType_enum.Vr)
        {
            MenuType_Mobile.SetActive(false);
            MenuType_VR.SetActive(true);
            MenuSystem = MenuType_VR.GetComponent<MenuSystem_GV>();
        }
    }

    //----------------------------------------------------------------------------------------------------//

    //Maybe put in Database section?
    public void SetFullGenomeSelection(Dictionary<string, string> genomeSettings)
    {
        GenomeSettings["Species"] = genomeSettings["Species"];
        GenomeSettings["GenomeSet"] = genomeSettings["GenomeSet"];
        GenomeSettings["CellType"] = genomeSettings["CellType"];
        GenomeSettings["ModelSet"] = genomeSettings["ModelSet"];
        GenomeSettings["Chromosome"] = genomeSettings["Chromosome"];
        GenomeSettings["Annotations"] = genomeSettings["Annotations"];//7777

        if (genomeSettings.ContainsKey("UserPosition"))
        {
            GenomeSettings["UserPosition"] = genomeSettings["UserPosition"];//7777
        }

        print("[GenomeManager_GV][SetFullGenomeSelection] " + GenomeSettings["Chromosome"]);
        GenomeMenu_Overview.Setup();
    }

    public string GetGenomeKey()
    {
        //string rootPath = Application.persistentDataPath + "/Genome Database/";
        string chromosomePath = "/" + GenomeSettings["Species"] + "/" + GenomeSettings["GenomeSet"] + "/Cells/" + GenomeSettings["CellType"] + "/Models/" + GenomeSettings["ModelSet"] + "/" + GenomeSettings["Chromosome"];// + ".csv";
        //string fullChromosomePath = rootPath + chromosomePath;

        return chromosomePath;
    }

    public string GetGenomeKeyMinimal()
    {
        //string rootPath = Application.persistentDataPath + "/Genome Database/";
        string chromosomePath = "/" + GenomeSettings["Species"] + "/" + GenomeSettings["GenomeSet"] + "/" + GenomeSettings["CellType"] + "/" + GenomeSettings["ModelSet"] + "/" + GenomeSettings["Chromosome"];// + ".csv";
        //string fullChromosomePath = rootPath + chromosomePath;

        return chromosomePath;
    }


    //----------------------------------------------------------------------------------------------------//
    // Database Function
    //----------------------------------------------------------------------------------------------------//

    #region "Database Functions (Get model / gene / annotations file paths)"//TODO move to database manager

    string GetChromosomeFile()
    {
        string rootPath = Application.persistentDataPath + "/Genome Database/";
        string chromosomePath = "/" + GenomeSettings["Species"] + "/" + GenomeSettings["GenomeSet"] + "/Cells/" + GenomeSettings["CellType"] + "/Models/" + GenomeSettings["ModelSet"] + "/" + GenomeSettings["Chromosome"];// + ".csv";
        string fullChromosomePath = rootPath + chromosomePath;

        return fullChromosomePath;
    }

    ///Users/chrisdrogaris/Library/Application Support/McGill/3DGV - 3D Genome Viewer/Genome Database//Human/GRCh38/Cells/Rao_HUVEC/Annotations/HIC00318_B/chr13.bed
    string GetAnnotationFile(string annotationKey)
    {
        string rootPath = Application.persistentDataPath + "/Genome Database/";

        //Pre / post
        string chromosome_prepend = "chr";
        string chromosome_extension = "bed";

        //Chromosome file
        string chromosomeFile = GenomeSettings["Chromosome"];
        string[] chromosomeFile_arr = GenomeSettings["Chromosome"].Split('.');
        chromosomeFile = chromosome_prepend + chromosomeFile_arr[0] + "." + chromosome_extension;

        //Chromosome path
        string chromosomePath = GenomeSettings["Species"] + "/" + GenomeSettings["GenomeSet"] + "/Cells/" + GenomeSettings["CellType"] + "/Annotations/" + annotationKey + "/" + chromosomeFile;// + ".csv";
        string fullChromosomePath = rootPath + chromosomePath;

        return fullChromosomePath;
    }

    #endregion

    //----------------------------------------------------------------------------------------------------
    // Delete all model / genes / annotations renders
    //----------------------------------------------------------------------------------------------------

    #region "Delete all genome renderes"

    public void DeleteGenomeRenders()
    {
        DataPanelManager.DeleteAll();
        ModelVisuals.DeleteAllModels();
        GeneVisuals.DeleteAllGenes();
        //AnnotationVisuals.DeleteAllAnnotations();//TODO add delete here
    }

    #endregion

    //----------------------------------------------------------------------------------------------------//
    // Enable / Disable Model 
    //----------------------------------------------------------------------------------------------------//

    #region "Enable Model"

    [Header("Model Display Type (LineRender/Regular)")]
    public string ModelType = "Regular";//LineRender/Regular

    public void EnableModel()
    {
        if (ModelType == "Regular")
        {
            EnableModel_Regular();
        }
        else
        if (ModelType == "LineRender")
        {
            EnableModel_LineRender();//Debug
        }
    }

    void EnableModel_Regular()
    {
        DataPanel_Manager.Setup();

        //Annotations
        AnnotationVisuals.DeleteAllAnnotations();
        if (ModelVisuals.CurrentModel != null)
        {
            GenomeSettings["Annotations"] = "";
        }

        AnnotationVisuals.Setup();

        //Get file
        string modelFileCSV = GetChromosomeFile();

        //Genes
        GeneVisuals.DeleteAllGenes();
        if (AutoEnableGene == "On")//
        {
            GenomeSettings["Genes"] = "On";
        }

        //Create model
        ModelVisuals.CreateModel(modelFileCSV);

        //Load overview
        GenomeMenu_Overview.Setup();

        //Enable Genes
        if (GenomeSettings["Genes"] == "On")//
        {
            GeneVisuals.ToggleOn();
        }

        //Save genome settings
        SaveGenomeSettings(GenomeSettings);

        //Center user
        if (GenomeSettings.ContainsKey("UserPosition") && GenomeSettings["UserPosition"] != "auto")
        {
            //Player.transform.position = bookmark.PositionVector;
            Vector3 userPosition = GetVector3(GenomeSettings["UserPosition"]);
            print("[GenomeManager_GV][EnableModel_Regular] : " + userPosition);
            print("[GenomeManager_GV][EnableModel_Regular] : " + GenomeSettings["UserPosition"]);
            Player.SetUserPosition(userPosition);
            GenomeSettings["UserPosition"] = "auto";
        }
        else
        {
            Player.CenterUser();
        }
        

        //Set player scale
        Player.SetWorldScale();

        //Setup hover
        HoverMeshManager.Setup();

        HoverMeshSelectedManager.Setup();

        DataPanelManager.DeleteAll();
        //----------//

        //Arrow System (Module)
        SetArrowSystem();
        ArrowSystem_Manager.Setup(ModelVisuals.CurrentModel, Player.Camera.GetComponent<Camera>());//TODO add camera

        ArrowSystem_Manager2.GetComponent<OffScreenIndicator>().Setup(Player.Camera.GetComponent<Camera>());
        ArrowSystem_Manager2.transform.parent.gameObject.SetActive(true);
    }

    Vector3 GetVector3(string rString)
    {
        string[] temp = rString.Substring(1, rString.Length - 2).Split(',');
        float x = float.Parse(temp[0]);
        float y = float.Parse(temp[1]);
        float z = float.Parse(temp[2]);
        Vector3 rValue = new Vector3(x, y, z);
        return rValue;
    }
    #endregion

    //----------------------------------------------------------------------------------------------------//
    // Enable / Disable Annotations 
    //----------------------------------------------------------------------------------------------------//

    #region "Annotations"

    //Initial load to enable saved annotations
    void EnabledAnnotations()
    {
        StartCoroutine(EnabledAnnotations_enum());
    }

    IEnumerator EnabledAnnotations_enum()
    {
        print("EnabledAnnotations_enum 1");
        yield return new WaitForSeconds(0.1f);

        string annotations = GenomeSettings["Annotations"];

        //Cancel
        if (annotations == "")
        {
            yield break;
        }

        List<string> annotationsList = annotations.Split(',').ToList();
        GenomeMenu_Overview.LoadAnnotationsState(annotationsList.Count);

        yield return null;


        //Wait for model to be generated
        while (ModelVisuals.ModelGeneratedComplete == false)
        {
            //Wait
        }
        print("EnabledAnnotations_enum 2" + annotationsList.Count);
        

        for (int i = 0; i < annotationsList.Count; i++)
        {
            print("EnabledAnnotations_enum 3");

            EnableAnnotation(annotationsList[i], true);

            //Wait for model to be generated
            //while (AnnotationVisuals.AnnotationGeneratedComplete == false)


            //Wait for it to be enabled
            while (AnnotationVisuals.EnabledAnnotations[annotationsList[i]].Enabled == false)
            {
                yield return null;
            }

            yield return null;

            yield return new WaitForSeconds(0.1f);

            print("EnabledAnnotations_enum 4");

            //Wait for annotation to be generated

        }

        //Set tabs state
        GenomeMenu_Overview.LoadAnnotationsState();

    }

    //GenomeManager.AnnotationVisuals.EnableAnnotation(selectedItem);
    public void EnableAnnotation(string annotation, bool loadBookmark = false)
    {
        //Get annotation file
        string annotationFile = GetAnnotationFile(annotation);

        //Enable annotation
        AnnotationVisuals.EnableAnnotation(annotation, annotationFile);

        if (loadBookmark != true)
        {
            SetEnabledAnnotations();

            SaveGenomeSettings(GenomeSettings);
        }
    }

    ///Users/chrisdrogaris/Library/Application Support/McGill/3DGV - 3D Genome Viewer/Genome Database//Human/GRCh38/Cells/Rao_HUVEC/Annotations/HIC00318_B/chr13.bed
    public void DisableAnnotation(string annotation)
    {
        string annotationFile = GetAnnotationFile(annotation);
        AnnotationVisuals.DisableAnnotation(annotation);

        SetEnabledAnnotations();

        SaveGenomeSettings(GenomeSettings);
    }

    public void UpdateAnnotation(string annotation, string color = "#FFFFFF")
    {
        AnnotationVisuals.UpdateAnnotation(annotation);

        SetEnabledAnnotations();

        SaveGenomeSettings(GenomeSettings);
    }

    //--------------------------------------------------//

    public void SetEnabledAnnotations()
    {
        string enabledAnnotations = AnnotationVisuals.GetEnabledAnnotations();

        GenomeSettings["Annotations"] = enabledAnnotations;
    }

    #endregion

    //----------------------------------------------------------------------------------------------------//
    // Load Default Bookmark / Last State
    //----------------------------------------------------------------------------------------------------//

    #region "Load Last State / Default Bookmark"

    void LoadLastBookmarkState()
    {
        if (AutoSetDefaultGenomeSettings)
        {
            if (PlayerPrefs.HasKey("SETTINGS__GenomeSettings") && PlayerPrefs.GetString("SETTINGS__GenomeSettings") != "")
            {
                GenomeSettings = LoadGenomeSetings();

                //GenomeMenu_DataSelection.SetGenomeSelection(GenomeSettings);
                GenomeMenu_DataSelection.Setup(GenomeSettings);
                EnableModel();
                //EnableGenes()//TODO move genes here
                EnabledAnnotations();
            }
            else
            {
                GenomeSettings = LoadDefaultBookmark();

                //GenomeMenu_DataSelection.SetGenomeSelection(GenomeSettings);
                GenomeMenu_DataSelection.Setup(GenomeSettings);
                EnableModel();
                //EnableGenes()//TODO move genes here
                EnabledAnnotations();
            }
        }
    }

    Dictionary<string, string> LoadDefaultBookmark()
    {
        return DefaultGenomeSettings;
    }

    #endregion

    //----------------------------------------------------------------------------------------------------//
    // Settings
    //----------------------------------------------------------------------------------------------------//

    #region "Set Settings"

    public void SetGenomeScale(float genomeScale)
    {
        GenomeScale = genomeScale;
    }

    public float GetGenomeScale()
    {
        float genomeScale = GenomeScale;
        float arMultiplier = 0.1f;

        if (Player.Mode == "Ar")
        {
            genomeScale *= arMultiplier;

            return genomeScale;
        }
        else
        {
            return genomeScale;
        }
    }

    public void SetAnnotationConfidenceMode(string annotationConfidenceMode)
    {
        AnnotationConfidenceMode = annotationConfidenceMode;

        //GenomeSettings["Genes"] = autoEnableGene;
    }

    public void SetAutoEnableGene(string autoEnableGene)
    {
        AutoEnableGene = autoEnableGene;

        GenomeSettings["Genes"] = autoEnableGene;
    }

    public void SetHoverSelectionWidth(int hoverSelectionWidth) 
    {
        HoverSelectionWidth = hoverSelectionWidth;//Stored value (not used)

        HoverMeshManager.HoverSelectionWidth = HoverSelectionWidth;
    }

    #endregion

    //----------------------------------------------------------------------------------------------------
    // Tutorial Auto Mode
    //----------------------------------------------------------------------------------------------------

    //[Header("Developer Mode")]
    //public GameObject DeveloperMode_Btn;
    //public string DeveloperModelEnabled = "Off";

    //public void SetDeveloperMode(string value)
    //{
    //    DeveloperModelEnabled = value;
    //}

    //----------------------------------------------------------------------------------------------------
    // Developer Mode
    //----------------------------------------------------------------------------------------------------

    [Header("Developer Mode")]
    public GameObject DeveloperMode_Btn;
    public string DeveloperModelEnabled = "Off";

    public void SetDeveloperMode(string value)
    {
        DeveloperModelEnabled = value;
    }

    public void ToggleDeveloperMode()
    {
        if (DeveloperModelEnabled == "On")
        {
            DeveloperMode_Btn.SetActive(true);
        }
        else
        if (DeveloperModelEnabled == "Off")
        {
            DeveloperMode_Btn.SetActive(false);
        }
    }


    //----------------------------------------------------------------------------------------------------
    // GenomeSettings TO GenomeSettingsKey
    //----------------------------------------------------------------------------------------------------

    #region "Save / Load Genome Settings"

    void SaveGenomeSettings(Dictionary<string,string> genomeSettings)
    {
        string genomeSettingsKey = GenomeSettingsToKey(genomeSettings);

        PlayerPrefs.SetString("SETTINGS__GenomeSettings", genomeSettingsKey);

        LogSystem.Instance.Log("<b>[GenomeManager_GV][SaveGenomeSettings][SETTINGS__GenomeSettings]:</b> " + genomeSettingsKey);
    }

    string GenomeSettingsToKey(Dictionary<string,string> genomeSettings)
    {
        string genomeSettingsKey = "/" + genomeSettings["Species"] + "/" + genomeSettings["GenomeSet"] + "/" + genomeSettings["CellType"] + "/" + genomeSettings["ModelSet"] + "/" + genomeSettings["Chromosome"] + "/" + genomeSettings["Genes"] + "/" + genomeSettings["Annotations"];

        return genomeSettingsKey;
    }

    //----------//

    Dictionary<string, string> LoadGenomeSetings()
    {
        string genomeSettings = PlayerPrefs.GetString("SETTINGS__GenomeSettings");

        LogSystem.Instance.Log("<b>[GenomeManager_GV][LoadGenomeSetings][SETTINGS__GenomeSettings]:</b> " + genomeSettings);

        return KeyToGenomeSettings(genomeSettings);
    }

    Dictionary<string, string> KeyToGenomeSettings(string genomeSettingsKey)
    {
        Dictionary<string, string> genomeSettings = new Dictionary<string, string>();

        string genomeSettings_str = PlayerPrefs.GetString("SETTINGS__GenomeSettings");
        string[] genomeSettings_arr = genomeSettings_str.Split('/');

        genomeSettings["Species"] = genomeSettings_arr[1];
        genomeSettings["GenomeSet"] = genomeSettings_arr[2];
        genomeSettings["CellType"] = genomeSettings_arr[3];
        genomeSettings["ModelSet"] = genomeSettings_arr[4];
        genomeSettings["Chromosome"] = genomeSettings_arr[5];
        genomeSettings["Genes"] = genomeSettings_arr[6];
        genomeSettings["Annotations"] = genomeSettings_arr[7];

        return genomeSettings;
    }

    #endregion

    //----------------------------------------------------------------------------------------------------
    // Player Genome Interaction Settings 
    //----------------------------------------------------------------------------------------------------

    #region "Player Genome Interaction"

    [Header("Interaction Settings")]
    public bool EnableInteraction = true;
    public Interaction_GV Interaction;
     
    //To disable interaction with genome when menu is open
    public void SetEnableInteraction(bool b)
    {
        StartCoroutine(SetEnableInteraction_enum(b));//777NEW
    }

    IEnumerator SetEnableInteraction_enum(bool b)
    {

        yield return new WaitForSeconds(0.1f);

        EnableInteraction = b;
        if (EnableInteraction)
        {
            Interaction.EnableInteraction = true;
        }
        else
        {
            Interaction.EnableInteraction = false;
        }

        LogSystem.Instance.Log("<b>[GenomeManager_GV][SetEnableInteraction]: </b> " + b + " //Enable genome interaction");

    }
    #endregion

}



//----------------------------------------------------------------------------------------------------//
// Setup Modules
//----------------------------------------------------------------------------------------------------//

//public void Setup_ArrowSystem()
//{

//}



//GenomeSettings["Genes"] = GeneVisuals.GetGeneState();// "Off";
//void LoadDefaultBookmark_original()
//{
//    GenomeSettings["Species"] = DefaultGenomeSettings["Species"];
//    GenomeSettings["GenomeSet"] = DefaultGenomeSettings["GenomeSet"];
//    GenomeSettings["CellType"] = DefaultGenomeSettings["CellType"];
//    GenomeSettings["ModelSet"] = DefaultGenomeSettings["ModelSet"];
//    GenomeSettings["Chromosome"] = DefaultGenomeSettings["Chromosome"];
//    GenomeSettings["Genes"] = DefaultGenomeSettings["Genes"];

//    GenomeMenu_DataSelection.SetGenomeSelection(GenomeSettings);

//    EnableModel();
//}

//ModelVisuals.Setup(modelFileCSV);
//ModelVisuals.RenderModel(modelFileCSV);


//print("SetFullGenomeSelection " + GenomeSettings["Species"]);
//print("SetFullGenomeSelection " + GenomeSettings["Species"]);

//public Model_GV Model;//Test model
//public string MenuType = "Mobile";
