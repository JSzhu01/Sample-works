using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using Sirenix.OdinInspector;
using Sirenix.Serialization;
using System;
/*
    The GenomeMenu_Overview class.
    Manages the overview section
    of the genome selection process.
*/
/// The GenomeMenu_Overview class.
/// Manages the overview section
/// of the genome selection process.
/// </summary>
public class GenomeMenu_Overview_GV : SerializedMonoBehaviour
{
    [Header("Reference Managers")]
    public GenomeManager_GV GenomeManager;
    public GenomeMenu_DataSelection_GV GenomeMenu_DataSelection;

    //----------//

    [Header("UI References (Text)")]
    //TODO just reference text elements
    [ShowInInspector]
    public Dictionary<string, GameObject> Sections = new Dictionary<string, GameObject>() {
        { "Species", null },
        { "GenomeSet", null },
        { "CellType", null },
        { "ModelSet", null },
        { "Chromosome", null },
        { "Genes", null },
        { "Annotations", null }
    };

    //--------------------------------------------------//

    // Start is called before the first frame update
    void Start()
    {
        //...//
    }

    // Update is called once per frame
    void Update()
    {
        //...//
    }

    private void OnEnable()
    {
        Setup();
    }

    //--------------------------------------------------//

    public void Setup()
    {
        //Setup genome model settings
        foreach (KeyValuePair<string, GameObject> entry in Sections)
        {
            Text button_txt = GetButtonText(entry.Key);
            button_txt.text = GenomeManager.GenomeSettings[entry.Key];

            //print("[GenomeMenu_Overview_GV][Setup] : " + entry.Value + " / " + " / " + entry.Key + " / " + GenomeManager.GenomeSettings[entry.Key] + " / " );//+  btn_obj.name 
        }





        //Setup genes setting
        //LoadGenesState();//TODO not used for Mobile

        //Setup annotations setting
        LoadAnnotationsState();
    }

    Text GetButtonText(string section)
    {
        GameObject btn = Sections[section];
        Text text = btn.transform.GetChild(0).gameObject.transform.GetChild(1).gameObject.transform.GetChild(0).gameObject.GetComponent<Text>();

        return text;
    }

    //----------------------------------------------------------------------------------------------------
    // Select Section Item
    //----------------------------------------------------------------------------------------------------

    public void SelectItem(string section)
    {
        GenomeManager.MenuSystem.CloseAllSections();//TEST
        GenomeMenu_DataSelection.gameObject.SetActive(true);
        LoadSection(section);
    }

    public void LoadSection(string section)
    {
        GenomeMenu_DataSelection.EnableSection(section);
        GenomeManager.SetEnableInteraction(false);

        GenomeManager.ModelVisuals.ModelCenter_Obj.SetActive(false);
    }




    //----------------------------------------------------------------------------------------------------
    // Genes
    //----------------------------------------------------------------------------------------------------

    void LoadGenesState()
    {
        string genesStateLabel = "Genes : ";
        string genesState = GenomeManager.GenomeSettings["Genes"];

        GameObject btn_obj = Sections["Genes"].transform.GetChild(0).gameObject.transform.GetChild(1).gameObject.transform.GetChild(0).gameObject;
        btn_obj.GetComponent<Text>().text = genesState;//genesStateLabel + 
    }

    //--------------------------------------------------//

    public void ToggleGenes()
    {
        if (GenomeManager.GenomeSettings["Genes"] == "On")
        {
            DisableGenes();
        }
        else
        if (GenomeManager.GenomeSettings["Genes"] == "Off")
        {
            EnableGenes();
        }
    }


    //--------------------------------------------------//

    void EnableGenes()
    {
        GenomeManager.GenomeSettings["Genes"] = "On";
        LoadGenesState();
        //Load genes in genome
    }

    void DisableGenes()
    {
        GenomeManager.GenomeSettings["Genes"] = "Off";
        LoadGenesState();
        //Load genes in genome
    }

    //----------------------------------------------------------------------------------------------------
    // Annotations
    //----------------------------------------------------------------------------------------------------

    public void LoadAnnotationsState(int annotationsEnabledCount = -1)
    {
        GameObject btn_obj = Sections["Annotations"].transform.GetChild(0).gameObject.transform.GetChild(1).gameObject.transform.GetChild(0).gameObject;

        //Manually set
        if (annotationsEnabledCount == -1)
        {
            annotationsEnabledCount = GenomeManager.AnnotationVisuals.GetAnnotationsEnabledCount();
        }

        btn_obj.GetComponent<Text>().text = annotationsEnabledCount.ToString();

        //string annotationsStateLabel = "Annotations : ";
        //string annotationsState = GenomeManager.GenomeSettings["Annotations"];
        //print("LoadAnnotationsState() " + annotationsEnabledCount);
    }

    //--------------------------------------------------//

}

//genesStateLabel + 

//public void Setup(Dictionary<string, GameObject> sections)
//{
//    Sections = sections;

//    Setup();
//}


//if (entry.Key != "Genes")
//{
//}

//void SetItem(string section)
//{
//    Sections[section].GetComponent<Button>().interactable = true;
//}


// transform.GetChild(0).transform.GetChild(1).transform.GetChild(0).transform.gameObject.GetComponent<Text>().text = GenomeManager.GenomeSettings[entry.Key];
// do something with entry.Value or entry.Key
// gameObject.transform.Find("Text").gameObject;
//print("a : " + entry.Key);
//print("b : " + entry.Value.name);
//print("a : " + btn_obj);


// Unity will not serialize. Serialized by Odin.
//[ShowInInspector]
//public Dictionary<string, GameObject> FirstDictionary;

// Unity will serialize. NOT serialized by Odin.
//public MyClass MyReference;

//[Serializable]
//public class MyClass
//{
// Despite the OdinSerialize attribute, this field is not serialized.
//[ShowInInspector]
//public Dictionary<string, GameObject> SecondDictionary = new Dictionary<string, GameObject>()
//{
//    { "Species", null },
//    { "GenomeSet", null },
//    { "CellType", null },
//    { "ModelSet", null },
//    { "Chromosomes", null }
//};