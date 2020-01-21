var api;
function showDiv(v){
    if (api)  //close any pop-ups that might already be open
        if (api.isOpened)
            api.close();

    if (!document.getElementById(v)) return;

    api=$('#'+v).overlay({
        mask: {color: '#000000'},   
        top:'0px',
        api: true           
    }).load();
}
