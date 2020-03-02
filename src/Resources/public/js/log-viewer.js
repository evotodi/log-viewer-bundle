const toggle = function (className, element) {
    let elements = document.getElementsByClassName(className.toLowerCase());
    for (let i = 0; i < elements.length; i++) {
        if(element.checked === true){
            elements[i].style.display = 'table-row';
        }else{
            elements[i].style.display = 'none';
        }
    }
};
