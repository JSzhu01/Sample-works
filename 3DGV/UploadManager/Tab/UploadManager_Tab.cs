using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class UploadManager_Tab : MonoBehaviour
{
    public string section;

    public GameObject lockedIcon;
    public GameObject unlockedIcon;
    public Text valueLabel;

    //
    public GameObject selectedBg;

    public bool sectionActive = false;

    /*--------------------------------------------------*/

    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    /*--------------------------------------------------*/

    public void SetEnabledSection()
    {
        UploadManager.Instance.SetEnabledSection(section);

        EnableSelectedBg();
    }

    /*--------------------------------------------------*/

    void EnableLock()
    {
        lockedIcon.SetActive(true);
        unlockedIcon.SetActive(false);
    }

    void DisableLock()
    {
        lockedIcon.SetActive(false);
        unlockedIcon.SetActive(true);
    }

    /*--------------------------------------------------*/

    //void Disable()
    //{
    //    DisableButton();
    //}

    //void Enable()
    //{
    //    EnableButton();
    //}


    public void DisableButton()
    {
        this.GetComponent<Button>().interactable = false;
        EnableLock();
    }

    public void EnableButton()
    {
        this.GetComponent<Button>().interactable = true;
        DisableLock();
    }

    /*--------------------------------------------------*/

    public void SetValue(string txt)
    {
        valueLabel.text = txt;
    }

    void ClearValue()
    {
        valueLabel.text = "---";
    }

    /*--------------------------------------------------*/

    public void DisableSelectedBg()
    {
        selectedBg.gameObject.SetActive(false);
    }

    public void EnableSelectedBg()
    {
        selectedBg.gameObject.SetActive(true);
    }



}
