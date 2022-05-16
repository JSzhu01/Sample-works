using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using Sirenix.OdinInspector;
using Sirenix.Serialization;
using System;

//Purpose is to control navigation between sections of genome selection
//Also set tabs status, next button and set buttons in each section
//Data is then communicated to GenomeSystem to set parameters
//Data is loaded from GenomeFileSystem script

/*
    The GenomeMenu_Overview class.
    Manages the overview section
    of the genome selection process.
*/
/// The GenomeMenu_Overview class.
/// Manages the overview section
/// of the genome selection process.
/// </summary>
public class GenomeMenu_DataSelection_GV : SerializedMonoBehaviour
{
    [Header("Genome Manager")]
    public GenomeManager_GV GenomeManager;

    [Header("Tab Buttons")]
    public GenomeMenu_Tabs_GV GenomeMenu_Tabs;

    //----------//

    [Header("Current Selected Section")]
    public GameObject CurrentSection;

    //----------//

    [Header("Genome Menu (Sections)")]
    // Unity will not serialize. Serialized by Odin.
    [ShowInInspector]
    public Dictionary<string, GameObject> Section = new Dictionary<string, GameObject>() {
        { "Species", null },
        { "GenomeSet", null },
        { "CellType", null },
        { "ModelSet", null },
        { "Chromosome", null },

        { "Annotations", null }
    };

    //----------//

    [ShowInInspector]
    public List<string> GenomeSectionOrder = new List<string>()
    {
        "Species",
        "GenomeSet",
        "CellType",
        "ModelSet",
        "Chromosome",

        "Annotations"
    };

    //----------//

    [Header("Genome Data (Pre-Selected Items Values)")]
    [ShowInInspector]
    public Dictionary<string, string> GenomeSelection = new Dictionary<string, string>() {
        { "Species", "" },
        { "GenomeSet", "" },
        { "CellType", "" },
        { "ModelSet", "" },
        { "Chromosome", "" },

        { "Annotations", "" }
    };

    [Header("Genome Data (Selected Buttons List)")]
    [ShowInInspector]
    public Dictionary<string, GameObject> GenomeSelection_Btns = new Dictionary<string, GameObject>() {
        { "Species", null },
        { "GenomeSet", null },
        { "CellType", null },
        { "ModelSet", null },
        { "Chromosome", null },

        { "Annotations", null }
    };

    //----------------------------------------------------------------------------------------------------

    // Start is called before the first frame update
    void Awake()
    {
        //...//
    }

    // Update is called once per frame
    void Update()
    {
        //...//
    }

    void OnEnable()
    {
        //GenomeSelection["Annotations"] = "";
    }

    //----------------------------------------------------------------------------------------------------

    public void Setup(Dictionary<string,string> genomeSettings)
    {
        GenomeSelection = genomeSettings;
    }

    //----------------------------------------------------------------------------------------------------

    public void EnableSection(string section)
    {
        DisableAllSections();

        Section[section].SetActive(true);
        GenomeMenu_Tabs.EnableSection(section, GenomeSelection);
        CurrentSection = Section[section];
    }

    void DisableAllSections()
    {
        foreach (var section in Section)
        {
            section.Value.SetActive(false);
        }
    }

    //--------------------------------------------------//

    public void LoadPreviousSection()
    {
        int sectionIndex = CurrentSection.transform.GetSiblingIndex();
        string nextSectionKey = GenomeSectionOrder[sectionIndex - 1];
        DisableAllSections();

        EnableSection(nextSectionKey);
    }

    public void LoadNextSection()
    {
        int sectionIndex = CurrentSection.transform.GetSiblingIndex();
        string nextSectionKey = GenomeSectionOrder[sectionIndex + 1];

        EnableSection(nextSectionKey);
    }

    //----------------------------------------------------------------------------------------------------
    // Genome Selection Actions
    //----------------------------------------------------------------------------------------------------

    public void StartGenomeSelection()
    {
        EnableSection("Species");
    }


    public void SetGenomeSelection(string key, string value, GameObject btn)
    {
        GenomeSelection_Btns[key] = btn;
        GenomeSelection[key] = value;

        Section[key].GetComponent<GenomeMenu_Section_GV>().EnableNextBtn();
    }

    public void SetGenomeSelection_MultiSelect(string key, string value, GameObject btn)
    {
        print("[GenomeMenu_DataSelection_GV][SetGenomeSelection_MultiSelect()] #3 " + key);
        print("[GenomeMenu_DataSelection_GV][SetGenomeSelection_MultiSelect()] #3a " + GenomeManager.GenomeSettings["Annotations"]);

        GenomeSelection_Btns[key] = btn;
        GenomeSelection[key] = value;

        print("[GenomeMenu_DataSelection_GV][SetGenomeSelection_MultiSelect()] #3b " + GenomeManager.GenomeSettings["Annotations"]);

        //Section[key].GetComponent<GenomeMenu_Section_GV>().EnableNextBtn();
    }

    //----------------------------------------------------------------------------------------------------//

    //***Enables model setup
    public void SetFullGenomeSelection()
    {
        GenomeManager.DeleteGenomeRenders();
        GenomeManager.SetFullGenomeSelection(GenomeSelection);

        GenomeManager.EnableModel();
    }

    public void LoadGenomeSelection()
    {
        EnableSection("Species");
        SetFullGenomeSelection();
    }

    //----------------------------------------------------------------------------------------------------//

}


//public void SetGenomeSelection(Dictionary<string, string> genomeSettings)
//{
//    ////Setup genome model settings
//    //foreach (KeyValuePair<string, string> entry in genomeSettings)
//    //{
//    //    GenomeSelection[entry.Key] = entry.Value;

//    //    if (entry.Key != "Genes")
//    //    {
//    //        print("entry.Key " + entry.Key);
//    //        GameObject btn_obj = GenomeSelection_Btns[entry.Key].transform.GetChild(0).gameObject.transform.GetChild(1).gameObject.transform.GetChild(0).gameObject;
//    //        btn_obj.GetComponent<Text>().text = GenomeManager.GenomeSettings[entry.Key];
//    //    }
//    //}
//}


//string currentSectionKey = CurrentSection.GetComponent<GenomeMenu_Section_GV>().name;
//string currentSectionKey = CurrentSection.GetComponent<GenomeMenu_Section_GV>().name;


// NextPhase_btn.GetComponent<Button>().interactable = true;

//LoadNextSection();

//SetupGenomeSelectionDictionary();



//[Header("Genome Selection Reference")]
//public GameObject GenomeSelectionContainer;


////Unity will serialize.NOT serialized by Odin.
//public MyClass MyReference;

//[Serializable]
//public class MyClass
//{
//    // Despite the OdinSerialize attribute, this field is not serialized.
//    [OdinSerialize]
//    public Dictionary<string, GameObject> SecondDictionary;
//}

//void SetupGenomeSelectionDictionary()
//{
//    foreach (Transform item in GenomeSelectionContainer.transform)
//    {

//        //GenomeSelection_Btns.Add(item.name, null);
//        //GenomeSection.Add(item.name, item.gameObject);

//        //GenomeSelection.Add(item.name, "");
//        //print(item.name);
//        //GenomeSectionOrder.Add(item.name);//[item.transform.GetSiblingIndex()] = 
//    }
//}


//public GameObject

//[Header("Genome Selection Dictionary")]
//[ShowInInspector]
//public Dictionary<string, GameObject> GenomeSelection_Btns = new Dictionary<string, GameObject>();


//[Header("Current Selection")]
//[ShowInInspector]
//public Dictionary<string, GameObject> GenomeSection = new Dictionary<string, GameObject>();


//public List<string> GenomeSectionOrder = List<string>(){"aaa"};



//print("nextSectionKey " + nextSectionKey);
//print("GetSiblingIndex " + sectionIndex);

//print("nextSectionKey " + nextSectionKey);
//print("GetSiblingIndex " + sectionIndex);


//for (int i=0; i< nextSectionKey.Length; i++)
//{

//}

//(sectionIndex + 1)

//for () {

//    GenomeSection[]
//}



//[Header("Genome Menu (Sections)")]
//[ShowInInspector]
//public Dictionary<string, GameObject> Section_DELETE = new Dictionary<string, GameObject>() {
//    { "Species", null },
//    { "GenomeSet", null },
//    { "CellType", null },
//    { "ModelSet", null },
//    { "Chromosome", null }
//};