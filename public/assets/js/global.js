const BaseModel = {

    loadToPrint:(url) =>{
        $("<iframe>")             // create a new iframe element
            .hide()               // make it invisible
            .attr("src", url)     // point the iframe to the page you want to print
            .appendTo("body");    // add iframe to the DOM to cause it to load the page
    },

    numberWithCommas:(amount) => {
        amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    },

    disabledProperties:(target,bool=true) =>{
        $("#"+target+" *").prop("disabled", bool);
    },

    commaSeparateNumber:(val) => {
        // remove sign if negative
        var sign = 1;
        if (val < 0) {
          sign = -1;
          val = -val;
        }
      
        // trim the number decimal point if it exists
        let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
      
        while (/(\d+)(\d{3})/.test(num.toString())) {
          // insert comma to 4th last position to the match number
          num = num.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }
      
        // add number after decimal point
        if (val.toString().includes('.')) {
          num = num + '.' + val.toString().split('.')[1];
        }
      
        // return result with - sign if negative
        return sign < 0 ? '-' + num : num;

    },

    _token: $("meta[name='_token']").attr("content"),
   
    myRights: $("meta[name='myRights']").attr("content"),

    otherPrev: JSON.parse($("meta[name='otherPrev']").attr("content")),

    findPrev: (val)=>{
        if (BaseModel.otherPrev!==null) {
            let res = BaseModel.otherPrev.some(elem=>{
                return elem.toLowerCase()===val.toLowerCase()
            })
    
            return res;
        }
    }


}

const toasMessage = (heading,text,icon) =>{
    $.toast({
        heading,text,icon,
        loader: true,        // Change it to false to disable loader
        loaderBg: '#9EC600'  // To change the background
    })
}

