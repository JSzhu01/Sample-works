using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class GenomeMenu_Item_GV : MonoBehaviour
{
    [Header("Reference Managers")]
    public GenomeMenu_Section_GV GenomeMenu_Section;
    public GenomeMenu_DataSelection_GV GenomeMenu_DataSelection;
    
    [Header("Section")]
    public string Section;

    //----------//

    [Header("Value")]
    public Text Value_text;
    public string Value;

    [Header("Selected")]
    public bool Selected = false;

    //--------------------------------------------------//

    //[Header("Section (Multi Select)")]
    //public string Section;

    [Header("Multi Select")]
    public bool MultiSelect = false;

    [Header("Multi Select Icon (On / Off Reference)")]
    public bool Enabled = false;
    public GameObject Enabled_icon;
    public GameObject Disabled_icon;

    //--------------------------------------------------//

    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    //----------------------------------------------------------------------------------------------------
    // Multi select - enabled icon / more
    //----------------------------------------------------------------------------------------------------

    public void EnableItem_MultiSelect(bool b)
    {
        //Enabled = b;

        SetEnabledIcon(b);
    }

    public void SetEnabledIcon(bool b)
    {
        if(b == true)
        {
            Enabled_icon.SetActive(true);
            Disabled_icon.SetActive(false);
        }
        else
        {
            Enabled_icon.SetActive(false);
            Disabled_icon.SetActive(true);
        }
    }


    //Clear existing children
    public void SelectItem_MultiSelect(bool disableAutoNext = false)
    {
        //Reset previos selections
        GenomeMenu_DataSelection.Section[Section].GetComponent<GenomeMenu_Section_GV>().MultiSelect_ResetSelection();

        print("[GenomeMenu_Item_GV][SelectItem()] #2 " + disableAutoNext);
        //Get selected item
        GameObject selected_btn = GenomeMenu_DataSelection.GenomeSelection_Btns[Section];

        //Reset existing selected item (to not selected)
        if (selected_btn != null)
        {
            selected_btn.GetComponent<Button>().interactable = true;
            GenomeMenu_DataSelection.GenomeSelection_Btns[Section] = null;
        }

        //Select item parameters (to selected)
        Selected = true;
        GetComponent<Button>().interactable = false;

        //Set new selected item in manager
        //GenomeMenu_DataSelection.SetGenomeSelection_MultiSelect(Section, Value, gameObject);//777
        GenomeMenu_Section.SetSelectedItem_MultiSelect(Value, gameObject);

        //New
        //EnableItem_MultiSelect(true);

        //Auto next section
        //if (GenomeMenu_Section.AutoNext == true) //&& autoNextDisabledOveride == false
        //{
        //    //GenomeMenu_Section.LoadNextSection();
        //}
    }

    //----------------------------------------------------------------------------------------------------
    // Setup
    //----------------------------------------------------------------------------------------------------

    public void Setup()
    {
        print("Setup() *** " + Value);
        Value_text.text = Value;
    }

    //----------------------------------------------------------------------------------------------------
    // Item Clicked
    //----------------------------------------------------------------------------------------------------

    //Clear existing children
    public void SelectItem()//
    {
        if (MultiSelect == true)
        {
            print("[GenomeMenu_Item_GV][SelectItem()] #1");
            SelectItem_MultiSelect();

        }
        else
        {
            SelectItem_SingleSelect();
        }
    }

    //--------------------------------------------------//

    public void SelectItem_SingleSelect()
    {
        //Get selected item
        GameObject selected_btn = GenomeMenu_DataSelection.GenomeSelection_Btns[Section];

        //Reset existing selected item
        if (selected_btn != null)
        {
            selected_btn.GetComponent<Button>().interactable = true;
            GenomeMenu_DataSelection.GenomeSelection_Btns[Section] = null;
        }

        //Select item parameters
        Selected = true;
        GetComponent<Button>().interactable = false;

        //Set new selected item in manager
        GenomeMenu_DataSelection.SetGenomeSelection(Section, Value, gameObject);

        //Auto next section
        if (GenomeMenu_Section.AutoNext == true) //&& autoNextDisabledOveride == false
        {
            GenomeMenu_Section.LoadNextSection();
        }
    }

    //----------//

    //Clear existing children
    public void SelectItem(bool disableAutoNext = false)
    {
        if (MultiSelect == true)
        {
            SelectItem_MultiSelect(disableAutoNext);
        }
        else
        {
            SelectItem_SingleSelect(disableAutoNext);
        }
    }

    int modeTmp = 2;
    //Clear existing children
    public void SelectItem_SingleSelect(bool disableAutoNext = false)
    {
        //Get selected item
        GameObject selected_btn = GenomeMenu_DataSelection.GenomeSelection_Btns[Section];

        //Reset existing selected item
        if (selected_btn != null)
        {
            selected_btn.GetComponent<Button>().interactable = true;
            GenomeMenu_DataSelection.GenomeSelection_Btns[Section] = null;
        }

        //Select item parameters
        Selected = true;

        if (modeTmp == 1 || GenomeMenu_Section.AutoNext == false)
        {
            GetComponent<Button>().interactable = false;
        }
        else
        {
            var colors = GetComponent<Button>().colors;
            colors.normalColor = GetComponent<Button>().colors.highlightedColor;

            GetComponent<Button>().colors = colors;
        }

        //Set new selected item in manager
        GenomeMenu_DataSelection.SetGenomeSelection(Section, Value, gameObject);

        //Auto next section
        if (GenomeMenu_Section.AutoNext == true) //&& autoNextDisabledOveride == false
        {
            //GenomeMenu_Section.LoadNextSection();
        }
    }


    public void PlaySound(string key)
    {
        GameObject.Find("AudioManager").GetComponent<AudioManager_GV>().PlaySound(key);
    }
}


//Load next section
//GenomeMenu_System.LoadNextSection();


//public GenomeMenu_System_GV GenomeMenu_System;



//public void SelectItem_MultiSelect()
//{
//    //Get selected item
//    GameObject selected_btn = GenomeMenu_DataSelection.GenomeSelection_Btns[Section];

//    //Reset existing selected item
//    if (selected_btn != null)
//    {
//        selected_btn.GetComponent<Button>().interactable = true;
//        GenomeMenu_DataSelection.GenomeSelection_Btns[Section] = null;
//    }

//    //Select item parameters
//    Selected = true;
//    GetComponent<Button>().interactable = false;

//    //Set new selected item in manager
//    GenomeMenu_DataSelection.SetGenomeSelection_MultiSelect(Section, Value, gameObject);

//    //New
//    //EnableItem_MultiSelect(true);

//    //Auto next section
//    //if (GenomeMenu_Section.AutoNext == true) //&& autoNextDisabledOveride == false
//    //{
//    //    //GenomeMenu_Section.LoadNextSection();
//    //}
//}