    
    //new folder prompt
    function newfolder(p){var n=prompt('New folder name','folder');if(n!==null&&n!=='')
    {
        window.location.search='p='+encodeURIComponent(p)+'&new='+encodeURIComponent(n);
    }
    }

    //rename prompt
    
    function rename(p,f){var n=prompt('New name',f);if(n!==null&&n!==''&&n!=f)
    {
        window.location.search='p='+encodeURIComponent(p)+'&ren='+encodeURIComponent(f)+'&to='+encodeURIComponent(n);
    }
    }

  
    //modal

    $(document).ready(function(){
        
        $('.modal').modal();
      });
              
   //filelist

    var $list = $( '#list' );
    var $options = $( '.option' );

    $list.on( 'change', function (e) {
      
      $options.hide();
      $( '#option-' + this.value ).show();
      
    } );

    //filelist

   
    //materialize

      $(document).ready(function() {
        $('select').material_select();
      });


   //copy url

        function Copy() {
      var Url = document.getElementById("url");
      Url.innerHTML = window.location.href;
      console.log(Url.innerHTML)
      Url.select();
      document.execCommand("copy");
    }


var $list = $( '#list' );
var $options = $( '.option' );

$list.on( 'change', function ( e ) {
  
  $options.hide();
  $( '#option-' + this.value ).show();
  
} );



  function makeFileList() {
      var input = document.getElementById("files");
      var ul = document.getElementById("fileList");
      while (ul.hasChildNodes()) {
        ul.removeChild(ul.firstChild);
      }
      for (var i = 0; i < input.files.length; i++) {
        var li = document.createElement("li");
        li.innerHTML = input.files[i].name;
        ul.appendChild(li);
      }
      if(!ul.hasChildNodes()) {
        var li = document.createElement("li");
        li.innerHTML = 'No Files Selected';
        ul.appendChild(li);
      }
    }   


   $(document).ready(function() {
  $('ul.tabs').tabs();
  $("#btnContinue").click(function() {
    $('ul.tabs').tabs('select_tab', 'test2');
  });
});


        


     
        