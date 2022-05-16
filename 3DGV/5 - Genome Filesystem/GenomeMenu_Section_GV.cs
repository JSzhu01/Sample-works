using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.Linq;
using UnityEngine.UI;
using System.IO;

public class GenomeMenu_Section_GV : MonoBehaviour
{
    //[Header("Genome Manager")]
    //public GenomeManager_GV GenomeManager;

    ////--------------------------------------------------//


    [Header("Reference Managers")]
    public GenomeMenu_DataSelection_GV GenomeMenu_DataSelection;

    [Header("Section")]
    public string Section = "";

    //----------//

    [Header("Items List")]
    public List<GameObject> Items;

    //----------//

    [Header("Item Container")]
    public GameObject ItemContainer;

    [Header("Item Prefab")]
    public GameObject Item_prefab;

    //----------//

    //[Header("Seleted Button")]
    //public GameObject ItemSelected_btn;

    [Header("Next Button")]
    public GameObject NextPhase_btn;

    //----------//

    [Header("Auto Next")]
    public bool AutoNext;

    public bool EnableLoadAllItems = true;

    //----------//

    public bool MultiSelect = false;

    public GameObject MultiSelect_SelectedItemBtn = null;
    public string MultiSelect_SelectedItemKey = "";

    public void SetSelectedItem_MultiSelect(string key, GameObject btn)
    {
        MultiSelect_SelectedItemKey = key;
        MultiSelect_SelectedItemBtn = btn;
    }

    //--------------------------------------------------//

    // Start is called before the first frame update
    void Start()
    {

    }

    // Update is called once per frame
    void Update()
    {

    }

    void OnEnable()
    {
        ClearAllItems();

        if (EnableLoadAllItems)
        {
            LoadAllItems();
        }
    }

    void OnDisable()
    {
        GenomeMenu_DataSelection.GenomeManager.SetEnableInteraction(true);
    }


    public void MultiSelect_ResetSelection()
    {
        for (int i=0; i<Items.Count; i++)
        {
            Items[i].GetComponent<GenomeMenu_Item_GV>().Selected = false;
            Items[i].GetComponent<Button>().interactable = true;
        }
    }

    /*----------------------------------------------------------------------------------------------------
    Clear / Load List
    ----------------------------------------------------------------------------------------------------*/

    //Clear existing children
    public void ClearAllItems()
    {
        foreach (Transform child in ItemContainer.transform)
        {
            GameObject.Destroy(child.gameObject);
        }
    }

    //Load thumbnails
    public void LoadAllItems()
    {
        //Get all items
        //Dictionary<string, string> items = GetItems();

        //Load images as thumbnails
        if (MultiSelect == true)
        {
            StartCoroutine(LoadItems_Multiselect_enum());//items
        }
        else
        {
            StartCoroutine(LoadItems_enum());//items
        }
    }

    IEnumerator LoadItems_enum()//Dictionary<string, string> items
    {
        Items.Clear();

        Dictionary<string, string> items = GetItems();

        yield return null;

        print("LoadItems_enum");
        //Create button objects
        //for (int i = 0; i < items.Count; i++)
        foreach(KeyValuePair<string, string> item in items)
        {
            print("LoadItems_enum" + item.Key + " / " + item.Value );

            GameObject item_tmp = Instantiate(Item_prefab, ItemContainer.transform);

            //Setup item
            GenomeMenu_Item_GV itemBtn = item_tmp.GetComponent<GenomeMenu_Item_GV>();
            //Managers
            itemBtn.GenomeMenu_DataSelection = GenomeMenu_DataSelection.GetComponent<GenomeMenu_DataSelection_GV>();
            itemBtn.GenomeMenu_Section = GetComponent<GenomeMenu_Section_GV>();
            //Item value
            itemBtn.Value = item.Key;
            itemBtn.Section = Section;
            itemBtn.name = item.Key;
            itemBtn.Setup();
            Items.Add(item_tmp);

            //Set item as selected (if previously selected)
            if (GenomeMenu_DataSelection.GenomeSelection[Section] == item.Key)
            {
                itemBtn.SelectItem(true);
            }

        }

        yield return null;

        //Sort items

    

    }

    IEnumerator LoadItems_Multiselect_enum()//Dictionary<string, string> items
    {
        Items.Clear();
        Dictionary<string, string> items = GetItems();

        yield return null;

        print("LoadItems_enum");
        //Create button objects
        //for (int i = 0; i < items.Count; i++)

        //List<string> selectedItems = GenomeMenu_DataSelection.GenomeSelection[Section].Split(',').ToList();
        List<string> selectedItems = GenomeMenu_DataSelection.GenomeManager.GenomeSettings[Section].Split(',').ToList();

        print("[GenomeMenu_Section_GV][LoadItems_Multiselect_enum][selectedItems] " + GenomeMenu_DataSelection.GenomeSelection[Section]);

        foreach (KeyValuePair<string, string> item in items)
        {
            print("[LoadItems_Multiselect_enum] LoadItems_enum : " + item.Key + " / " + item.Value);

            GameObject item_tmp = Instantiate(Item_prefab, ItemContainer.transform);

            //Setup item
            GenomeMenu_Item_GV itemBtn = item_tmp.GetComponent<GenomeMenu_Item_GV>();
            //Managers
            itemBtn.GenomeMenu_DataSelection = GenomeMenu_DataSelection.GetComponent<GenomeMenu_DataSelection_GV>();
            itemBtn.GenomeMenu_Section = GetComponent<GenomeMenu_Section_GV>();
            //Item value
            itemBtn.Value = item.Key;
            itemBtn.Section = Section;
            itemBtn.name = item.Key;
            itemBtn.Setup();
            Items.Add(item_tmp);

            //Set item as selected (if previously selected)
            //List<string> selectedItems = GenomeMenu_DataSelection.GenomeSelection[Section].Split(',').ToList();
            print("[GenomeMenu_Section_GV][LoadItems_Multiselect_enum][selectedItems][key] " + item.Key);

            //if (GenomeMenu_DataSelection.GenomeSelection[Section] == item.Key)
            /*if (selectedItems.Contains(item.Key))
            {
                print("[GenomeMenu_Section_GV][LoadItems_Multiselect_enum][selectedItems][key] " + "INSIDE");
                //itemBtn.SelectItem(true);

                itemBtn.EnableItem_MultiSelect(true);
                //itemBtn.SelectItem_MultiSelect();
            }
            else
            {

            }*/
        }

        yield return null;
    }
    /*----------------------------------------------------------------------------------------------------
    Get List Items
    ----------------------------------------------------------------------------------------------------*/

    Dictionary<string, string> GetItems()
    {
        print("GenomeMenu_Section - GetItems()");
        Dictionary<string, string> items = GenomeMenu_DataSelection.GenomeManager.Database.GetDatabaseItems(GenomeMenu_DataSelection.GenomeSelection, Section);


        print("GenomeMenu_Section - GetItems()" + items.Count);

        //string[] files = Directory.GetFiles(path).OrderBy(f => int.Parse(Path.GetFileNameWithoutExtension(f))).ToArray();
        //var sortedDict = from entry in items orderby entry.Value ascending select entry;
        //Dictionary<string, string> itemsSorted = new Dictionary<string, string>();
        //foreach (KeyValuePair<string,string> item in itemsSorted)
        //{
        //    //Console.WriteLine(value);
        //    itemsSorted.Add(item.Key, item.Value);
        //}

        //foreach (KeyValuePair<string, string> item in items.OrderBy(i => int.Parse(i.Key) )) //Path.GetFileNameWithoutExtension(i)
        //{
        //    itemsSorted.Add(item.Key, item.Value);

        //    //Debug.WriteLine(person.Key.LastName + ", " + person.Key.FirstName + " - Id: " + person.Value.ToString());
        //}

        //print("GetItems()");
        //foreach (KeyValuePair<string, string> item in items)
        //{
        //    print("GetItems() - " + item.Key);

        //    //Debug.WriteLine(person.Key.LastName + ", " + person.Key.FirstName + " - Id: " + person.Value.ToString());
        //}


        return items;
    }

    /*----------------------------------------------------------------------------------------------------
    Load Section
    ----------------------------------------------------------------------------------------------------*/

    public void LoadNextSection()
    {
        GenomeMenu_DataSelection.LoadNextSection();
    }

    public void LoadPreviousSection()
    {
        GenomeMenu_DataSelection.LoadPreviousSection();
    }

    /*----------------------------------------------------------------------------------------------------
    Next Button
    ----------------------------------------------------------------------------------------------------*/

    public void EnableNextBtn()
    {
        NextPhase_btn.GetComponent<Button>().interactable = true;
    }

    public void DeactivateNextBtn()
    {
        NextPhase_btn.GetComponent<Button>().interactable = false;
    }
}

//[Header("Reference Managers")]
//public GenomeMenu_System_GV GenomeMenu_System;


//List<string> listItems;

//listItems = new List<string>() { "AAA", "BBB", "CCC" };


//LogSystem.Instance.Log("<b>[Bookmark System][View All]</b> LoadImages_enum / file count : " + files.Count);



//LogSystem.Instance.Log("<b>[Bookmark System][View All]</b> LoadAllBookmarks: ");

//Ser references to screenshot folder + extension
//string path = BookmarkSystem.screenshot_folder;
//string extension = "*.jpg";

//Get list of files in folder
//List<string> files = BookmarkSystem.GetFilesInPath(path, extension);
//List<string> files = BookmarkSystem.GetFilesInPath(path, extension);
//string bookmarksJson = PlayerPrefs.GetString("BookmarksJSON");