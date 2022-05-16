using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class GenomeMenu_Annotations_Confidence : MonoBehaviour
{
    public GenomeManager_GV GenomeManager;

    public GameObject ConfidenceOn_btn;
    public GameObject ConfidenceOff_btn;

    //--------------------------------------------------//

    // Start is called before the first frame update
    void Start()
    {
        
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    //--------------------------------------------------//

    private void OnEnable()
    {
        string annotationConfidenceMode = GenomeManager.Settings.GetSavedSetting("SETTING__AnnotationConfidenceMode");

        if (annotationConfidenceMode == "On")
        {
            ToggleButton(true);
            //GenomeManager.Settings.SetAnnotationConfidenceMode("SETTING__AnnotationConfidenceMode", "On");
            GenomeManager.Settings.GenomeManager.Settings.SettingsManager.UpdateSettings("SETTING__AnnotationConfidenceMode", "On");
        }
        else
        {
            ToggleButton(false);
            //GenomeManager.Settings.SetAnnotationConfidenceMode("SETTING__AnnotationConfidenceMode", "Off");
            GenomeManager.Settings.GenomeManager.Settings.SettingsManager.UpdateSettings("SETTING__AnnotationConfidenceMode", "Off");

        }
    }

    public void ToggleButton(bool b)
    {
        if (b)
        {
            ConfidenceOn_btn.SetActive(true);
            ConfidenceOff_btn.SetActive(false);

            GenomeManager.Settings.UpdateSettings("SETTING__AnnotationConfidenceMode", "On");
        }
        else
        {
            ConfidenceOn_btn.SetActive(false);
            ConfidenceOff_btn.SetActive(true);

            GenomeManager.Settings.UpdateSettings("SETTING__AnnotationConfidenceMode", "Off");
        }
    }
}
