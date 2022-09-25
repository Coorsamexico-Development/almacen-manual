export const formatoMoney = (number) => {
    const exp = /(\d)(?=(\d{3})+(?!\d))/g;
    const rep = '$1,';
    return number.toString().replace(exp, rep);
};

export function removeClass(ele, cls) {
    if (hasClass(ele, cls)) {
        var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
        ele.className = ele.className.replace(reg, ' ');
    }
}

export function selectElement(ele) {
    removeClass(ele, 'text-gray-500');
    ele.classList.add("bg-gray-700");
    ele.classList.add("text-white");
    ele.querySelector('td:first-child').style.backgroundColor = "#1F2937";
    ele.querySelector('td:first-child').style.color = "white";
}

export function removeSelect(ele) {
    removeClass(ele, 'bg-gray-700');
    removeClass(ele, 'text-white');
    ele.classList.add("text-gray-500");
    ele.querySelector('td:first-child').removeAttribute("style")
}

function hasClass(ele, cls) {
    return ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}



export function saveUserStorage(user) {
    if (user != undefined) {
        localStorage.setItem('userName', user);
    }

}
/**
 * Alamcena los datos de session de un usuario
 */
export function userData() {
    const saveName = localStorage.getItem('userName');
    if (saveName != null) {
        const saveName = localStorage.getItem('userName');
        document.getElementById('firstLeter').innerHTML = saveName[0].toUpperCase();
        document.getElementById('fullName').innerHTML = saveName;
    } else {
        document.getElementById('firstLeter').innerText = 'B';
        document.getElementById('fullName').innerHTML = 'Bienvenido';
    }

}
