import { useState, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';
import './style.scss';

export default function Edit(props) {


    const [employeePreview, setPreview] = useState([]) // 1. post is currently []
    const [employee_names, setNames] = useState([]) // 1. post is currently []
    const [employee_Ids, setEmployeeIds] = useState([]) // 1. post is currently []
    
    var allNames = [];
    var allEmployeeIds = [];

    useEffect(() => { 
        fetchNames();
        fetchEmployeePreview();
    }, [props.attributes.employeeId])

    const fetchNames = async () => {
        
        apiFetch(  { path: `employeedetail/v1/namesAndIds` } ).then((response) => {

               var data = response;
                for(var i in data.names){
                    allNames.push(data.names[i]);
                }
                for(var i in data.ids){
                    allEmployeeIds.push(data.ids[i]);
                }
                    
                setNames(allNames);
                setEmployeeIds(allEmployeeIds);
            
            
        });
        
    }


    const fetchEmployeePreview = async () => {

        if (props.attributes.employeeId >= 0){

            
            apiFetch(  { path: `employeedetail/v1/preview/${props.attributes.employeeId}`} ).then((response) => {
            
                    var data = response;
                    
                    setPreview(response.preview); //For Edit Screen

                    props.attributes.name = data.employeeData['name'];
                    props.attributes.description = data.employeeData['description'];
                    props.attributes.avatarURL = data.employeeData['avatarURL'];
                    props.attributes.position = data.employeeData['position'];
                    props.attributes.socialLinks = data.employeeData['socialLinks'];
                    props.attributes.employeePreview = response.preview; //For Frontend Screen
  
            });


        }
        
        
    }


    
    return (
        <div>
            
            <select onChange={e => props.setAttributes({employeeId: e.target.value})} >
            <option value = '-1' selected={props.attributes.employeeId == -1 } > Please Select an Employee </option>
            {employee_names.map((names, index) =>
                
             <option value = {employee_Ids[index]} selected={props.attributes.employeeId == employee_Ids[index] }> {names}</option>
              )}
            </select>

            <div dangerouslySetInnerHTML={{__html : employeePreview}} >

            </div>
        </div>
    )


}


