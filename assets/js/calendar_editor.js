export function check_cell_status (obj) {
    if (obj.start_from_8 && obj.busy){
        return "busy";
    }
    else if(obj.start_from_8){
        return "busy-start_from_8";
    }
    else if(obj.start_from_20){
        return "busy-start_from_20";
    }
    else if(obj.end_to_20){
        return "busy-start_from_8";
    }
    else if(obj.end_to_8){
        return "free";
    }
    else if(obj.busy){
        return "busy";
    }
    else{
        return "free";
    }
}




