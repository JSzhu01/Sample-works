using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using System.Linq;

public class UploadManager : MonoBehaviour
{
    [Header("Managers References")]
    public UploadManager_Delete UploadManager_Delete;
    public UploadManager_CreateFolder UploadManager_CreateFolder;
    public UploadManager_UploadFile UploadManager_UploadFile;
    public UploadManager_BatchUpload UploadManager_BatchUpload;

    public UploadManager_DeleteAll UploadManager_DeleteAll;
    public UploadManager_Rename UploadManager_Rename;

    [Header("Upload Manager")]
    //public FileBrowserTest UploadFileSystem;
    //public UploadManager_UploadFile UploadFileSystem;

    [Header("Sections Order")]
    public int UploadManager_CurrentSection = 1;

    [Header("Auto Next Button")]
    public GameObject AutoNextEnabledBtn;
    public GameObject AutoNextDisabledBtn;
    public bool autoNext = false;

    /*-----*/

    [Header("Selected Database")]

    public string Species = "";
    public string GenomeSet = "";
    public string CellType = "";
    public string ModelSet = "";
    public string Models = "";
    public string Annotations = "";
    public string Genes = "";

    public Dictionary<string, string> Data;

    /*-----*/

    [Header("Sections")]

    public GameObject Species_section;
    public GameObject GenomeSet_section;
    public GameObject CellType_section;
    public GameObject ModelSet_section;
    public GameObject Models_section;
    public GameObject Annotations_section;
    public GameObject Genes_section;

    Dictionary<string, GameObject> SectionContainers;

    /*-----*/

    [Header("NextButtonReference Database Btn")]

    public GameObject Species_nextBtn;
    public GameObject GenomeSet_nextBtn;
    public GameObject CellType_nextBtn;
    public GameObject ModelSet_nextBtn;
    public GameObject Models_nextBtn;
    public GameObject Annotations_nextBtn;
    public GameObject Genes_nextBtn;

    Dictionary<string, GameObject> SectionNextBtns;

    /*-----*/

    [Header("Section Buttons (Tabs)")]

    public Button Species_btn;
    public Button GenomeSet_btn;
    public Button CellType_btn;
    public Button ModelSet_btn;
    public Button Models_btn;
    public Button Annotations_btn;
    public Button Genes_btn;

    Dictionary<string, Button> SectionBtns;

    /*-----*/

    [Header("Selected Database Btn")]

    public GameObject Species_selectedBtn;
    public GameObject GenomeSet_selectedBtn;
    public GameObject CellType_selectedBtn;
    public GameObject ModelSet_selectedBtn;
    public GameObject Models_selectedBtn;
    public GameObject Annotations_selectedBtn;
    public GameObject Genes_selectedBtn;

    Dictionary<string, GameObject> SectionSelectedBtns;

    /*--------------------------------------------------*/

    Dictionary<int, string> SectionOrder = new Dictionary<int, string>(){
        { 1, "Species" },
        { 2, "GenomeSet" },
        { 3, "CellType" },
        { 4, "ModelSet" },
        { 5, "Models" },
        { 6, "Annotations" },
        { 7, "Genes" }
    };

    /*--------------------------------------------------*/

    public static UploadManager Instance
    {
        get
        {
            if (instance == null)
                instance = FindObjectOfType(typeof(UploadManager)) as UploadManager;

            return instance;
        }
        set
        {
            instance = value;
        }
    }
    private static UploadManager instance;

    /*--------------------------------------------------*/

    private void Awake()
    {
        Data = new Dictionary<string, string>(){
            { "Species", "" },
            { "GenomeSet", "" },
            { "CellType", "" },
            { "ModelSet", "" },
            { "Models", "" },
            { "Annotations", "" },
            { "Genes", "" }
        };

        SectionNextBtns = new Dictionary<string, GameObject>(){
            { "Species", Species_nextBtn },
            { "GenomeSet", GenomeSet_nextBtn },
            { "CellType", CellType_nextBtn},
            { "ModelSet", ModelSet_nextBtn},
            { "Models", Models_nextBtn},
            { "Annotations", Annotations_nextBtn},
            { "Genes", Genes_nextBtn}
        };

        SectionSelectedBtns = new Dictionary<string, GameObject>(){
            { "Species", Species_selectedBtn },
            { "GenomeSet", GenomeSet_selectedBtn },
            { "CellType", CellType_selectedBtn},
            { "ModelSet", ModelSet_selectedBtn},
            { "Models", Models_selectedBtn},
            { "Annotations", Annotations_selectedBtn},
            { "Genes", Genes_selectedBtn}
        };

        SectionBtns = new Dictionary<string, Button>(){
            { "Species", Species_btn },
            { "GenomeSet", GenomeSet_btn },
            { "CellType", CellType_btn},
            { "ModelSet", ModelSet_btn},
            { "Models", Models_btn},
            { "Annotations", Annotations_btn},
            { "Genes", Genes_btn}
        };

        SectionContainers = new Dictionary<string, GameObject>(){
            { "Species", Species_section },
            { "GenomeSet", GenomeSet_section },
            { "CellType", CellType_section },
            { "ModelSet", ModelSet_section },
            { "Models", Models_section },
            { "Annotations", Annotations_section },
            { "Genes", Genes_section }
        };
    }

    /*--------------------------------------------------*/

    // Start is called before the first frame update
    void Start()
    {
        SetEnabledSection("Species");
        SetupAutoNextBtn();
    }

    // Update is called once per frame
    void Update()
    {
        //...//
    }

    /*----------------------------------------------------------------------------------------------------
    Database - Set / Get
    ----------------------------------------------------------------------------------------------------*/

    public string GetSectionValue(string s)
    {
        string section_value = Data[s];

        return section_value;
    }

    public void SetSectionValue(string s, string v)
    {
        Data[s] = v;
    }

    /*----------------------------------------------------------------------------------------------------
    Navigation Tabs - Previous / Next
    ----------------------------------------------------------------------------------------------------*/

    public void EnablePreviousSection()
    {
        if (UploadManager_CurrentSection - 1 > 0)
        {
            UploadManager_CurrentSection--;
            SetEnabledSection(SectionOrder[UploadManager_CurrentSection]);
        }
    }

    public void EnableNextSection()
    {
        if (UploadManager_CurrentSection + 1 <= SectionOrder.Count)
        {
            UploadManager_CurrentSection++;
            SetEnabledSection(SectionOrder[UploadManager_CurrentSection]);
        }
    }

    /*----------------------------------------------------------------------------------------------------
    In Sections Buttons - Previous / Next
    ----------------------------------------------------------------------------------------------------*/

    public void ResetSectionNextButton()
    {
        foreach (KeyValuePair<string, GameObject> entry in SectionNextBtns)
        {
            entry.Value.GetComponent<Button>().interactable = false;
        }
    }

    public void EnableSectionNextButton(string s)
    {
        SectionNextBtns[s].GetComponent<Button>().interactable = true;
    }

    /*----------------------------------------------------------------------------------------------------
    Enable Sections
    ----------------------------------------------------------------------------------------------------*/

    public void SetEnabledSection(string section)
    {
        //Reset enabled sections
        ResetEnabledSections();

        //Reset highlight off
        ResetSelectedTabs();

        //Enable correct buttons
        SetEnabledTabs();

        //Disable next stage buttons
        ResetSectionNextButton();

        //Get order number by section name and set section order
        var section_to_order = SectionOrder.FirstOrDefault(x => x.Value == section).Key;
        UploadManager_CurrentSection = section_to_order;

        //Load section by name
        LoadSectionContent(section);
    }

    //Load section content
    void LoadSectionContent(string s)
    {
        //Clear list
        SectionContainers[s].GetComponent<UploadManager_DirectoryContent>().ClearList();

        //Enable selected button
        SectionBtns[s].GetComponent<UploadManager_Tab>().EnableSelectedBg();

        //Enable section + populate content
        SectionContainers[s].GetComponent<UploadManager_DirectoryContent>().SetUploadSectionValue(s);
        SectionContainers[s].GetComponent<UploadManager_DirectoryContent>().LoadDirectoryContent();
        SectionContainers[s].GetComponent<UploadManager_DirectoryContent>().SetSelectedButton(Data[s]);

        SectionContainers[s].SetActive(true);
    }

    /*----------------------------------------------------------------------------------------------------
    Reset Sections
    ----------------------------------------------------------------------------------------------------*/

    //Reset enabled sections
    void ResetEnabledSections()
    {
        Species_section.SetActive(false);
        GenomeSet_section.SetActive(false);
        CellType_section.SetActive(false);
        ModelSet_section.SetActive(false);
        Models_section.SetActive(false);
        Annotations_section.SetActive(false);
        Genes_section.SetActive(false);
    }

    //Reset selected tabs
    void ResetSelectedTabs()
    {
        SectionBtns["Species"].gameObject.GetComponent<UploadManager_Tab>().DisableSelectedBg();
        SectionBtns["GenomeSet"].gameObject.GetComponent<UploadManager_Tab>().DisableSelectedBg();
        SectionBtns["CellType"].gameObject.GetComponent<UploadManager_Tab>().DisableSelectedBg();
        SectionBtns["ModelSet"].gameObject.GetComponent<UploadManager_Tab>().DisableSelectedBg();
        SectionBtns["Models"].gameObject.GetComponent<UploadManager_Tab>().DisableSelectedBg();
        SectionBtns["Annotations"].gameObject.GetComponent<UploadManager_Tab>().DisableSelectedBg();
        SectionBtns["Genes"].gameObject.GetComponent<UploadManager_Tab>().DisableSelectedBg();
    }

    public void ClearSectionValue(string s)
    {
        Data[s] = "";
        if (SectionSelectedBtns[s] != null)
        {
            SectionSelectedBtns[s].GetComponent<UploadManager_Button>().DeselectItem();
        }
    }

    /*----------*/

    //Set Enabled Buttons
    void SetEnabledTabs()
    {
        SectionBtns["Species"].GetComponent<UploadManager_Tab>().EnableButton();

        if (Data["Species"] != "")
        {
            SectionBtns["GenomeSet"].GetComponent<UploadManager_Tab>().EnableButton();
        }

        if (Data["GenomeSet"] != "")
        {
            SectionBtns["CellType"].GetComponent<UploadManager_Tab>().EnableButton();
        }

        if (Data["CellType"] != "")
        {
            SectionBtns["ModelSet"].GetComponent<UploadManager_Tab>().EnableButton();
        }

        if (Data["ModelSet"] != "")
        {
            SectionBtns["Models"].GetComponent<UploadManager_Tab>().EnableButton();
            SectionBtns["Annotations"].GetComponent<UploadManager_Tab>().EnableButton();
            SectionBtns["Genes"].GetComponent<UploadManager_Tab>().EnableButton();
        }
    }

    /*----------------------------------------------------------------------------------------------------
    Set max sections
    ----------------------------------------------------------------------------------------------------*/

    //Set max section
    public void SetMaxSection(string s, string v)
    {
        string section_value = GetSectionValue(s);

        if (section_value != v && UploadManager_CurrentSection <= 4)//v != "" && 
        {
            foreach (var element in SectionOrder.OrderByDescending(x => x.Key))
            {
                if (s == element.Value)
                {
                    return;
                }

                ResetSection(element.Value);
            }
        }
    }

    void ResetSection(string s)
    {
        Data[s] = "";

        SetSectionTabValue(s, "");
        SectionBtns[s].GetComponent<UploadManager_Tab>().DisableButton();
    }

    /*----------------------------------------------------------------------------------------------------
    Tab Navigation Buttons
    ----------------------------------------------------------------------------------------------------*/

    public void SetSectionSelectedItemBtn(string s, GameObject v)
    {
        SectionSelectedBtns[s] = v;
    }

    public void SetSectionTabValue(string s, string v)
    {
        SectionBtns[s].GetComponent<UploadManager_Tab>().SetValue(v);
    }

    /*----------------------------------------------------------------------------------------------------
    Add Item Popup Window - not sure where used
    ----------------------------------------------------------------------------------------------------*/

    public void OpenAddItemDialog(string s)
    {
        UploadManager_UploadFile.Setup(s, true);
        //UploadManager_UploadFile.SetUploadSectionValue(s);
        //UploadManager_UploadFile.gameObject.SetActive(true);
    }


    /*----------------------------------------------------------------------------------------------------
    Get Database Path
    ----------------------------------------------------------------------------------------------------*/

    //Gets relative database path
    public string GetRelativeDatabasePath(string s)
    {
        //Directory path temp
        string DatabaseTargetPath = "";

        //Database path
        string DatabasePath = DatabaseManager.Instance.GetDatabasePath();

        switch (s)
        {
            case "Species":
                DatabaseTargetPath = "/";
            break;

            case "GenomeSet":
                DatabaseTargetPath = "/" + Data["Species"] + "/";
            break;

            case "CellType":
                DatabaseTargetPath = "/" + Data["Species"] + "/" + Data["GenomeSet"] + "/Cells/";
            break;

            case "ModelSet":
                DatabaseTargetPath = "/" + Data["Species"] + "/" + Data["GenomeSet"] + "/Cells/" + Data["CellType"] + "/Models/";
            break;

            case "Models":
                DatabaseTargetPath = "/" + Data["Species"] + "/" + Data["GenomeSet"] + "/Cells/" + Data["CellType"] + "/Models/" + Data["ModelSet"] + "/";
            break;

            case "Annotations":
                DatabaseTargetPath = "/" + Data["Species"] + "/" + Data["GenomeSet"] + "/Cells/" + Data["CellType"] + "/Annotations/";
            break;

            case "Genes":
                DatabaseTargetPath = "/" + Data["Species"] + "/" + Data["GenomeSet"] + "/Genes/";
            break;
        }

        return DatabaseTargetPath;
    }

    /*----------------------------------------------------------------------------------------------------
    Setup Auto Mode
    ----------------------------------------------------------------------------------------------------*/


    //Initialize auto mode, on root scene, if on, reload database cache
    //void InitializeAutoNext()
    //{
    //    SetupAutoNextBtn();
    //}

    //Set auto mode on / off
    public void SetupAutoMode(bool b)
    {
        if (b == true)
        {
            PlayerPrefs.SetString("upload_manager_auto_next", "false");
            autoNext = false;
        }
        else
        {
            PlayerPrefs.SetString("upload_manager_auto_next", "true");
            autoNext = true;
        }
    }

    //UI set auto mode button toggle
    public void SetupAutoNextBtn()
    {
        if (PlayerPrefs.HasKey("upload_manager_auto_next") && PlayerPrefs.GetString("upload_manager_auto_next") == "true")
        {
            AutoNextDisabledBtn.SetActive(false);
            AutoNextEnabledBtn.SetActive(true);
            autoNext = true;
        }
        else
        {
            PlayerPrefs.SetString("upload_manager_auto_next", "false");
            AutoNextDisabledBtn.SetActive(true);
            AutoNextEnabledBtn.SetActive(false);
            autoNext = false;
        }
    }
}

//DatabasePath + 

//targetpath = "/" + Species + "/" + GenomeSet + "/Cells/" + CellType + "/Models/" + ModelSet;


//Set section
//switch (section)
//{
//    case "Species":
//        UploadManager_CurrentSection = 1;
//        LoadSectionContent("Species");
//    break;

//    case "GenomeSet":
//        UploadManager_CurrentSection = 2;
//        LoadSectionContent("GenomeSet");
//    break;

//    case "CellType":
//        UploadManager_CurrentSection = 3;
//        LoadSectionContent("CellType");
//    break;

//    case "ModelSet":
//        UploadManager_CurrentSection = 4;
//        LoadSectionContent("ModelSet");
//    break;

//    case "Models":
//        UploadManager_CurrentSection = 5;
//        LoadSectionContent("Models");
//    break;

//    case "Annotations":
//        UploadManager_CurrentSection = 6;
//        LoadSectionContent("Annotations");
//    break;

//    case "Genes":
//        UploadManager_CurrentSection = 7;
//        LoadSectionContent("Genes");
//    break;
//}


//Reset to enabled off 
//ResetEnabledTabs();


//SectionBtns[s].GetComponent<UploadManager_Tab>().DisableSelectedBg();
//SectionBtns[s].interactable = false;
//ClearSectionValue(s);
//SectionBtns[s].GetComponent<UploadManager_Tab>().DisableSelectedBg();


//if (element.Value != s)
//{
//    print("element.Value " + element.Value);

//    //ResetSection(element.Value);
//}
//else
//{
//    return;
//}

//foreach (KeyValuePair<string, Button> entry in SectionBtns)
//{
//    // do something with entry.Value or entry.Key
//    // entry.Value.GetComponent<Button>().interactable = false;
//}
//Reset enabled tab
//void ResetEnabledTabs()
//{
//    //SectionBtns["Species"].GetComponent<UploadManager_Tab>().DisableButton();
//    //SectionBtns["GenomeSet"].GetComponent<UploadManager_Tab>().DisableButton();
//    //SectionBtns["CellType"].GetComponent<UploadManager_Tab>().DisableButton();
//    //SectionBtns["ModelSet"].GetComponent<UploadManager_Tab>().DisableButton();
//    //SectionBtns["Models"].GetComponent<UploadManager_Tab>().DisableButton();
//    //SectionBtns["Annotations"].GetComponent<UploadManager_Tab>().DisableButton();
//    //SectionBtns["Genes"].GetComponent<UploadManager_Tab>().DisableButton();
//}

////Clears selected feedback from tabs (from inside button function)
//void ResetSelectedButtonsBg()
//{
//    //SectionBtns["Species"].GetComponent<UploadManager_Tab>().EnableButton();
//    //SectionBtns["GenomeSet"].GetComponent<UploadManager_Tab>().EnableButton();
//    //SectionBtns["CellType"].GetComponent<UploadManager_Tab>().EnableButton();
//    //SectionBtns["ModelSet"].GetComponent<UploadManager_Tab>().EnableButton();
//    //SectionBtns["Models"].GetComponent<UploadManager_Tab>().EnableButton();
//    //SectionBtns["Annotations"].GetComponent<UploadManager_Tab>().EnableButton();
//    //SectionBtns["Genes"].GetComponent<UploadManager_Tab>().EnableButton();
//}

//if (CellType != "")
//{
//    Models_btn.interactable = true;
//    Annotations_btn.interactable = true;
//    Genes_btn.interactable = true;
//}
//Clear item list
//void ClearList(GameObject c)
//{
//    foreach (Transform child in c.transform)
//    {
//        GameObject.Destroy(child.gameObject);
//    }
//}


//switch (s)
//{
//    case "Species":
//        Species_selectedBtn = v;
//    break;

//    case "GenomeSet":
//        GenomeSet_selectedBtn = v;
//    break;

//    case "CellType":
//        CellType_selectedBtn = v;
//    break;

//    case "ModelSet":
//        ModelSet_selectedBtn = v;
//    break;

//    case "Models":
//        Models_selectedBtn = v;
//    break;

//    case "Annotations":
//        Annotations_selectedBtn = v;
//    break;

//    case "Genes":
//        Genes_selectedBtn = v;
//    break;
//}

//switch (s)
//{
//    case "Species":
//        Species_btn.GetComponent<UploadManager_Tab>().SetValue(v);
//    break;

//    case "GenomeSet":
//        GenomeSet_btn.GetComponent<UploadManager_Tab>().SetValue(v);
//    break;

//    case "CellType":
//        CellType_btn.GetComponent<UploadManager_Tab>().SetValue(v);
//    break;

//    case "ModelSet":
//        ModelSet_btn.GetComponent<UploadManager_Tab>().SetValue(v);
//    break;

//    case "Models":
//        Models_btn.GetComponent<UploadManager_Tab>().SetValue(v);
//    break;

//    case "Annotations":
//        Annotations_btn.GetComponent<UploadManager_Tab>().SetValue(v);
//    break;

//    case "Genes":
//        Genes_btn.GetComponent<UploadManager_Tab>().SetValue(v);
//    break;
//}
//switch (s)
//{
//    case "Species":
//        break;

//    case "GenomeSet":
//        UploadFileSystem.SetUploadSectionValue(s);
//        break;

//    case "CellType":
//        UploadFileSystem.SetUploadSectionValue(s);
//        break;

//    case "ModelSet":
//        UploadFileSystem.SetUploadSectionValue(s);
//        break;

//    case "Models":
//        UploadFileSystem.SetUploadSectionValue(s);
//        break;

//    case "Annotations":
//        UploadFileSystem.SetUploadSectionValue(s);
//        break;

//    case "Genes":
//        UploadFileSystem.SetUploadSectionValue(s);
//        break;
//}

//public List<GameObject> UploadManager_Section;

//if (Species != "")
//{
//    Species_btn.interactable = true;
//    Species_btn.GetComponent<UploadManager_Tab>().EnableSelectedBg();
//}

//return;
//Models_btn.GetComponent<UploadManager_Tab>().EnableSelectedBg();
//if (GenomeSet == "")
//{
//    Species_btn.interactable = true;
//    GenomeSet_btn.interactable = true;
//    CellType_btn.interactable = false;
//    Models_btn.interactable = false;
//    Annotations_btn.interactable = false;
//    Genes_btn.interactable = false;

//    Species_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    GenomeSet_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    //CellType_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    //Models_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    //Annotations_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    //Genes_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();

//    return;
//    //Models_btn.GetComponent<UploadManager_Tab>().EnableSelectedBg();
//}

//if (Species != "")
//{
//    Species_btn.interactable = true;
//    CellType_btn.interactable = true;

//    //Species_btn.GetComponent<UploadManager_Tab>().EnableSelectedBg();
//}

//if (GenomeSet != "")
//{
//    CellType_btn.interactable = true;
//    //CellType_btn.GetComponent<UploadManager_Tab>().EnableSelectedBg();
//}



//if (CellType != "")
//{
//    Annotations_btn.interactable = true;
//    //Annotations_btn.GetComponent<UploadManager_Tab>().EnableSelectedBg();
//}

//if (CellType != "")
//{
//    Genes_btn.interactable = true;
//    //Genes_btn.GetComponent<UploadManager_Tab>().EnableSelectedBg();
//}


//void EnableActiveTabs()
//{
//    if (Species != "" || 1 == 1)
//    {

//    }

//    if (GenomeSet != "")
//    {

//    }

//    if (CellType != "")
//    {

//    }
//}

//void ResetEnabledBgButtons()
//{
//    Species_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    GenomeSet_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    CellType_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    Models_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    Annotations_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//    Genes_btn.GetComponent<UploadManager_Tab>().DisableSelectedBg();
//}