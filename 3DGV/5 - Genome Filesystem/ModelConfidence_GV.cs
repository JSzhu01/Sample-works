using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using Sirenix.Serialization;
using Sirenix.OdinInspector;

using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.IO;
using System.Linq;
using System;
using System.Collections;
using System.Collections.Generic;
using System.Threading;
using System.Threading.Tasks;
using UnityEngine;
using LitJson;

public class ModelConfidence_GV : MonoBehaviour
{
    public GenomeManager_GV GenomeManager;

    [Header("Reference Managers")]
    public GenomeMenu_DataSelection_GV GenomeMenu_DataSelection;
    public GenomeMenu_Section_GV GenomeMenu_Section;

    [Header("Section")]
    public string Section = "";

    //--------------------------------------------------//

    //[ShowInInspector]
    //public Dictionary<string, float> AnnotationConfidenceDict = new Dictionary<string, float>();
    [ShowInInspector]
    public Dictionary<string, Dictionary<string, float>> AnnotationConfidenceFullDict = new Dictionary<string, Dictionary<string, float>>();

    [ShowInInspector]
    Dictionary<string, float> ConfidenceDict = new Dictionary<string, float>();
    //--------------------------------------------------//

    // Start is called before the first frame update
    void Start()
    {

    }

    // Update is called once per frame
    void Update()
    {

    }

    public void Setup()
    {
        GetConfidenceScores();
    }

    //Temp solution, need to get full settings even if annotation screen not enabled on first load //TODO
    public float GetConfidenceScore(string chromosome)//string model, 
    {
        if (ConfidenceDict.Count == 0)
        {
            return -2;
        }
        else
        if (ConfidenceDict.ContainsKey(chromosome))
        {
            return ConfidenceDict[chromosome];
        }
        else
        {
            return -2;
        }
        //if ()
        //{

        //}
    }

    //--------------------------------------------------//

    IEnumerator SetConfidenceScores()
    {
        yield return new WaitForSeconds(0.1f);

        string chromosomeSetting = GenomeManager.GenomeSettings["Chromosome"];
        chromosomeSetting = Path.GetFileNameWithoutExtension(chromosomeSetting);
        string chromosome = chromosomeSetting;// "1";


        Dictionary<string, string> items = GenomeMenu_DataSelection.GenomeManager.Database.GetDatabaseItems(GenomeMenu_DataSelection.GenomeSelection, Section);

        for (int i = 0; i < items.Count; i++)
        {
            print("SetConfidenceScores " + i);
            string annotation = GenomeMenu_Section.Items[i].name;

            float confidenceScore = -1f;// AnnotationConfidenceFullDict[annotation][chromosome];

            if (AnnotationConfidenceFullDict.ContainsKey(annotation) && AnnotationConfidenceFullDict[annotation].ContainsKey(chromosome))
            {
                confidenceScore = AnnotationConfidenceFullDict[annotation][chromosome];
            }
            else
            {
                //confidenceScore = -1;
            }
            GenomeMenu_Section.Items[i].GetComponent<AnnotationConfidence_Item>().SetConfidence(confidenceScore);
        }

    }

    void GetConfidenceScores()
    {

        print("GenomeMenu_DataSelection.GenomeSelection " + GenomeMenu_DataSelection.GenomeSelection);
        //Annotation items
        //Dictionary<string, string> items = GenomeManager.Database.GetDatabaseItems(GenomeMenu_DataSelection.GenomeSelection, Section);

        //Get file
        //string miniPath = GenomeManager.GenomeSettings["Species"];
        string miniPath = GenomeManager.GenomeSettings["Species"] + "/" + GenomeManager.GenomeSettings["GenomeSet"] + "/Cells/" + GenomeManager.GenomeSettings["CellType"] + "/Models/" + GenomeManager.GenomeSettings["ModelSet"] + ".json";// + GenomeSettings["Chromosome"];// + ".csv";
        print("miniPath " + miniPath);

        string fileFullPath = Application.persistentDataPath + "/Genome Database" +"/" + miniPath;
        //"/Users/chrisdrogaris/Library/Application Support/McGill/3DGV - 3D Genome Viewer/Genome Database/Human/GRCh38/Cells/Rao_HUVEC/Annotations/HIC00318_A.json";
        print("fileFullPath " + fileFullPath);
        //Clear dictionary
        AnnotationConfidenceFullDict.Clear();

        //foreach (KeyValuePair<string, string> item in items)
        //{
        //    print("*key " + item.Key + " - " + item.Value);

        // Dictionary<string, float> singleAnnotationModelDict = new Dictionary<string, float>();
        //AnnotationConfidenceFullDict.Add(item.Key, singleAnnotationModelDict);

            fileFullPath = fileFullPath;// item.Value + ".json";

        //Get annotation db set
        //If exists read file
        //string targetFolder = Application.persistentDataPath + "/DiseaseGenesDB_JSON/";
            ConfidenceDict.Clear();

            //Create folder
            if (File.Exists(fileFullPath))
            {
                StreamReader readStream = new StreamReader(fileFullPath);
                string fileText = readStream.ReadToEnd();
                print("fileText : " + fileText);

                //Set confidence score in dict
                JsonData data = JsonMapper.ToObject(fileText);



                var deserializedObject = JsonMapper.ToObject(fileText);

                Dictionary<string, float> dict = new Dictionary<string, float>();

                foreach (var key in deserializedObject.Keys)
                {
                    var value = deserializedObject[key];

                    string k = key.ToString();
                    float v = float.Parse(value.ToString());

                    print("--- k " + key);
                    print("--- v " + value);

                    //AnnotationConfidenceDict.Add(k, v);
                    dict.Add(k, v);
                }

                ConfidenceDict = dict;

                //AnnotationConfidenceFullDict.Add(item.Key, dict);
                //AnnotationConfidenceFullDict.Add(item.Key, dict);
        }
            else
            {
                //TEST
            }
        //}

        //Get file path
        //Update values on annotation section

        /////StartCoroutine(SetConfidenceScores());
    }
}
