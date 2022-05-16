using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class UploadManager_Button : MonoBehaviour
{
    [Header("Section")]
    public string section;

    [Header("Item Name")]
    public Text label;
    public string value;

    [Header("Path")]
    public string path;
    public string path_relative;

    /*----------------------------------------------------------------------------------------------------*/

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

    /*----------------------------------------------------------------------------------------------------*/

    public void Setup(string s, string l, string pr, string pa)
    {
        section = s;
        label.text = l;
        value = l;
        path = pa;
        path_relative = pr;
    }

    /*----------------------------------------------------------------------------------------------------*/

    public void SelectItem()
    {
        //Check if current section has item selected
        //and clicked item isn't the same, disable next steps sections
        UploadManager.Instance.SetMaxSection(section, value);
        UploadManager.Instance.ClearSectionValue(section);
        UploadManager.Instance.SetSectionValue(section, value);
        UploadManager.Instance.SetSectionTabValue(section, value);
        UploadManager.Instance.SetSectionSelectedItemBtn(section, this.gameObject);
        UploadManager.Instance.EnableSectionNextButton(section);

        //Disable button interactability
        this.GetComponent<Button>().interactable = false;

        //Auto next
        if(UploadManager.Instance.autoNext)
        {
            UploadManager.Instance.EnableNextSection();
        }
    }

    public void DeselectItem()
    {
        //Enable button interactability
        this.GetComponent<Button>().interactable = true;
    }

    /*----------------------------------------------------------------------------------------------------*/

    public void DeleteItem()
    {
        UploadManager.Instance.UploadManager_Delete.Setup(section, path_relative, path, true);
    }

    public void EditItem()
    {
        UploadManager.Instance.UploadManager_Rename.Setup(section, path_relative, path, true);
    }
}





//UploadManager.Instance.UploadManager_Delete.Delete(path);
//path = "/Users/macbookpro/Library/Application Support/McGill/3DGV - 3D Genome Viewer/Genome Database/test/test.png";
