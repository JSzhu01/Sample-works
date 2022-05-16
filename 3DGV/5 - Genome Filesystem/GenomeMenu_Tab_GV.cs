using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class GenomeMenu_Tab_GV : MonoBehaviour
{
    [Header("Reference Manager")]
    public GenomeMenu_Tabs_GV GenomeMenu_Tabs;

    [Header("Section")]
    public string Section;

    [Header("States")]
    public string State = "Locked";//Unlocked, Locked, Selected, Hover
    public bool Selected = false;//Not used
    public bool Enabled = false;//Not used
    public string Val = "";
    public string DefaultVal = "---";

    [Header("Colors")]
    public Color[] Colors;

    [Header("Text")]
    public Text Label;
    public Text Value;
    public Text Blank;

    [Header("Border")]
    public Image Border;

    [Header("Icons")]
    public Image LockedIcon;
    public Image UnlockedIcon;
    public Image SetIcon;

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

    //--------------------------------------------------//

    //Locked, Unlocked, Selected
    public void Setup(string state, string value = "", bool selected = false)
    {
        Reset();

        //Set tab values
        State = state;
        Val = value;
        Selected = selected;

        //Unlocked + checked
        SetState(state);
    }

    void Reset()
    {
        State = "Locked";
        Val = "";
        Selected = false;
        Enabled = false;

        SetState("Locked");
    }

    //--------------------------------------------------//

    //Enables correct images / objects
    void SetState(string state)
    {
        switch (state)
        {
            case "Locked":
                SetState_Locked();
            break;

            case "Unlocked":
                SetState_Unlocked();
            break;

            case "Selected":
                SetState_Selected();
            break;

            case "Hover":
                SetState_Hover();
            break;
        }
    }

    //--------------------------------------------------//

    void SetState_Locked()
    {
        //Icons
        LockedIcon.gameObject.SetActive(true);
        UnlockedIcon.gameObject.SetActive(false);
        SetIcon.gameObject.SetActive(false);

        SetIcon.color = GetStateColor("Locked");

        //Border color
        Border.color = GetStateColor("Locked");

        //Text colors
        Label.color = GetStateColor("Locked");
        Value.color = GetStateColor("Locked");
        Blank.color = GetStateColor("Locked");

        //Interactable
        GetComponent<Button>().interactable = false;

        //Highlighted colors
        ColorBlock buttonColors = GetComponent<Button>().colors;
        buttonColors.normalColor = GetStateColor("Locked");
        buttonColors.disabledColor = GetStateColor("Locked");
        GetComponent<Button>().colors = buttonColors;

        //Value
        Value.text = DefaultVal;
    }

    void SetState_Unlocked()
    {
        //Icons
        LockedIcon.gameObject.SetActive(false);
        UnlockedIcon.gameObject.SetActive(false);
        SetIcon.gameObject.SetActive(false);

        if (Val != "")
        {
            SetIcon.gameObject.SetActive(true);
            SetIcon.color = GetStateColor("Unlocked");
        }
        else
        {
            UnlockedIcon.gameObject.SetActive(true);
            UnlockedIcon.color = GetStateColor("Unlocked");
        }

        //Border color
        Border.color = Color.white;

        //Icon color
        UnlockedIcon.color = GetStateColor("Unlocked");

        //Text colors
        Label.color = GetStateColor("Unlocked");
        Value.color = GetStateColor("Unlocked");
        Blank.color = GetStateColor("Unlocked");

        //Interactable
        GetComponent<Button>().interactable = true;

        //Highlighted colors
        ColorBlock buttonColors = GetComponent<Button>().colors;
        buttonColors.normalColor = GetStateColor("Unlocked");
        buttonColors.highlightedColor = GetStateColor("Selected");
        GetComponent<Button>().colors = buttonColors;

        Value.text = Val;
    }

    void SetState_Selected()
    {
        //Icons
        LockedIcon.gameObject.SetActive(false);
        UnlockedIcon.gameObject.SetActive(false);
        SetIcon.gameObject.SetActive(false);

        if (Val != "")
        {
            SetIcon.gameObject.SetActive(true);
            SetIcon.color = GetStateColor("Selected");
        }
        else
        {
            UnlockedIcon.gameObject.SetActive(true);
            UnlockedIcon.color = GetStateColor("Selected");
        }

        //Border color
        Border.color = GetStateColor("Selected");

        //Text colors
        Label.color = GetStateColor("Selected");
        Value.color = GetStateColor("Selected");
        Blank.color = GetStateColor("Selected");

        //Interactable
        GetComponent<Button>().interactable = false;

        //Highlighted colors
        ColorBlock buttonColors = GetComponent<Button>().colors;
        buttonColors.disabledColor = GetStateColor("Selected");
        GetComponent<Button>().colors = buttonColors;

        //Value
        if (Val == "")
        {
            Value.text = DefaultVal;
        }
        else
        {
            Value.text = Val;
        }

    }

    void SetState_Hover()
    {
        //Icons
        //LockedIcon.gameObject.SetActive(true);
        //UnlockedIcon.gameObject.SetActive(false);
        //SetIcon.gameObject.SetActive(false);

        //Icon colors
        UnlockedIcon.color = GetStateColor("Highlighted");
        SetIcon.color = GetStateColor("Highlighted");

        //Border color
        Border.color = Color.white;

        //Text colors
        Label.color = GetStateColor("Highlighted");
        Value.color = GetStateColor("Highlighted");
        Blank.color = GetStateColor("Highlighted");

        //Interactable
        //GetComponent<Button>().interactable = false;

        //Highlighted colors
        ColorBlock buttonColors = GetComponent<Button>().colors;
        buttonColors.normalColor = GetStateColor("Highlighted");
        buttonColors.highlightedColor = GetStateColor("Highlighted");
        GetComponent<Button>().colors = buttonColors;

        //Value
        Value.text = Val;
    }

    //--------------------------------------------------
    // Tab Button Interaction
    //--------------------------------------------------

    public void SelectItem()
    {
        GenomeMenu_Tabs.LoadSection(Section);
    }

    //--------------------------------------------------//

    public void HoverEnterItem()
    {
        print("HoverEnterItem");

        if (State == "Unlocked")
        {

            SetState("Hover");
        }
    }

    public void HoverExitItem()
    {
        SetState(State);
    }

    //--------------------------------------------------
    // Get state color
    //--------------------------------------------------

    Color GetStateColor(string state)
    {
        switch (state)
        {
            case "Locked":
                return Colors[0];
            break;

            case "Unlocked":
                return Colors[1];
            break;

            case "Selected":
                return Colors[2];
            break;

            case "Highlighted":
                return Colors[2];
            break;

            default:
                return Colors[0];
            break;
        }
    }

    public void PlaySound(string key)
    {
        if (GetComponent<Button>().interactable == true)
        {
            GameObject.Find("AudioManager").GetComponent<AudioManager_GV>().PlaySound(key);
        }
    }
}


//if (State == "Unlocked")
//{
//}

//print("HoverEnterItemw");

////Text colors
//Label.color = GetStateColor("Highlighted");
//Value.color = GetStateColor("Highlighted");
//Blank.color = GetStateColor("Highlighted");

////Icon colors
//UnlockedIcon.color = GetStateColor("Highlighted");


//public List<string> States = new List<string>() { "Unlocked", "Locked", "Set", "Hover" };


//--------------------------------------------------//

//void SetSelected()
//{
//    if (Selected)
//    {
//        SetState_Selected();
//    }
//    else
//    {
//        SetState_Deselected();
//    }
//}

//void SetState_Selected()
//{
//    Border.color = GetStateColor("Set");
//}

//void SetState_Deselected()
//{
//    if (Enabled)
//    {
//        Border.color = GetStateColor("Unlocked");
//    }
//    else
//    {
//        Border.color = GetStateColor("Locked");
//    }
//}



//print("HoverEnterItem()");
//SetState("Hover");

//SetState("Hover");



//            case "Set":
//                SetState_Selected();
//break;


//void SetIcon(string b)
//{
//    LockedIcon.gameObject.SetActive(false);
//    UnlockedIcon.gameObject.SetActive(false);
//    SetIcon.gameObject.SetActive(true);
//}

//--------------------------------------------------//


/*void SetState_Set()
{
    //Icons
    LockedIcon.gameObject.SetActive(false);
    UnlockedIcon.gameObject.SetActive(false);
    SetIcon.gameObject.SetActive(true);

    //Text colors
    Label.color = GetStateColor("Set");
    Value.color = GetStateColor("Set");
    Blank.color = GetStateColor("Set");

    //Interactable
    GetComponent<Button>().interactable = false;

    //Highlighted colors
    ColorBlock buttonColors = GetComponent<Button>().colors;
    buttonColors.disabledColor = GetStateColor("Set");
    GetComponent<Button>().colors = buttonColors;

    //Value
    Value.text = Val;
}*/

//SetState(State);
//SetSelected();
//public Image LockedIcon;
//public Image UnlockedIcon;
//public Image SetIcon;