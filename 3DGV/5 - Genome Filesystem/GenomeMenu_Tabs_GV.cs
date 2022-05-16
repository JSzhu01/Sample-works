using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using Sirenix.OdinInspector;

public class GenomeMenu_Tabs_GV : SerializedMonoBehaviour
{
    //--------------------------------------------------//

    [Header("GenomeMenu System")]
    public GenomeMenu_DataSelection_GV GenomeMenu_DataSelection;

    //--------------------------------------------------//

    [Header("Tab Buttons Reference")]
    public GameObject TabButtonsContainer;

    [Header("Tab Buttons Dictionary")]
    [ShowInInspector]
    public Dictionary<string, GameObject> TabButtons = new Dictionary<string, GameObject>();

    //----------------------------------------------------------------------------------------------------//

    void Awake()
    {
        //...//
    }

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

    //----------------------------------------------------------------------------------------------------//

    public void LoadSection(string section)
    {
        GenomeMenu_DataSelection.EnableSection(section);
    }

    public void EnableSection(string k, Dictionary<string, string> genomeSettings)
    {
        foreach (var item in TabButtons)
        {
            string key = item.Key;

            if (GenomeMenu_DataSelection.GenomeSelection[item.Key] != "")
            {
                item.Value.GetComponent<GenomeMenu_Tab_GV>().Setup("Unlocked", genomeSettings[key]);
            }
            else
            {
                item.Value.GetComponent<GenomeMenu_Tab_GV>().Setup("Locked", genomeSettings[key]);
            }
        }

        TabButtons[k].GetComponent<GenomeMenu_Tab_GV>().Setup("Selected", genomeSettings[k], true);
    }

}





//[Header("Containers Reference")]
//public GameObject SectionsContainer;

//[Header("Menu Items / Sections Dictionary")]
//[ShowInInspector]
//public Dictionary<string, GameObject> SectionPanels = new Dictionary<string, GameObject>();

//SetupSectionPanelsDictionary();
//SetupTabButtonsDictionary();
//DisableAllSections();

//SectionPanels[section].SetActive(true);
//EnableTabSection(section);

//[Header("Button Tabs")]
////public Button DataSet_btn;
//public Button Species_btn;
//public Button GenomeSet_btn;
//public Button CellType_btn;
//public Button ModelSet_btn;
//public Button Chromosome_btn;

/*[Header("Containers")]
//public Button DataSet_btn;
public GameObject Species_panel;
public GameObject GenomeSet_panel;
public GameObject CellType_panel;
public GameObject ModelSet_panel;
public GameObject Chromosome_panel;*/
//    public GameObject MainContainer;

/*
 * 
 * Button states :
 * Icon : Locked, Unlocked, Checkmarked
 * Enabled | 
 *      Current Tab / 
 * Disabled | 
 *      Current tab /
 * 
*/

//----------------------------------------------------------------------------------------------------
// Tab Sections
//----------------------------------------------------------------------------------------------------

//void SetupSectionPanelsDictionary()
//{
//    foreach (Transform item in SectionsContainer.transform)
//    {
//        SectionPanels.Add(item.name, item.gameObject);
//    }
//}

//void DisableAllSections()
//{
//    foreach (var item in SectionPanels)
//    {
//        item.Value.SetActive(false);
//    }
//}

//----------------------------------------------------------------------------------------------------
// Tab Buttons
//----------------------------------------------------------------------------------------------------

//void SetupTabButtonsDictionary()
//{
//    foreach (Transform item in TabButtonsContainer.transform)
//    {
//        TabButtons.Add(item.name, item.gameObject);
//    }
//}