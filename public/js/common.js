// Add EwayBill Id to localstorage 
// id, add - true/false
function addeWayIdLocalStorate(id, add){
    let i = localStorage.getItem("storage_eWayId");
    if(i==null){
        localStorage.setItem("storage_eWayId",JSON.stringify([]));
    }
    let getItem = $.parseJSON(localStorage.getItem("storage_eWayId"));
    console.log(id,add);
    if(add && !getItem.includes(id)){
        getItem.push(id);
    }
    if(!add)    
        getItem = getItem.filter(item => item !== id)

    localStorage.setItem("storage_eWayId",JSON.stringify(getItem) )
    console.log($.parseJSON(localStorage.getItem("storage_eWayId")));
}

function clearEwayIdsLocalStorage(){
    localStorage.removeItem("storage_eWayId");
}

function getEwayIdsLocalStorage(){
    return localStorage.getItem("storage_eWayId");
}
