  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <script type="text/javascript" src="lib/jquery-2.1.1.min.js"></script>
    <script type="text/javascript">
      console.log("a");
              /*$.post('cmpJSON.php',params,'json').done(
                //console.log("ok");
                function(data,textStatus){
                  for(var i=0 ; i<data.length;i++){
                    $('.data').text(data[i].price.text);
                  }
                }  
              )
              $.getJSON("cmpJSON.php",function(data){
                for(var i = 0; i<data.length;i++){
                  $("#data").append(data[i].price);
                  console.log(data[i].price);
                }
              });
  */

  function table(){
    $.ajax({
      type:'GET',
      url:'./tableJSON.php',
      dataType:'json',
      success:function(data){
        console.log("success");
        console.log(data); 
        $(function(){
          $('.result').remove(); 
          for(var i=0;i<data.length;i++){
            var newElement=document.createElement("div"); 
            newElement.className='result';
            var id=1;
            newElement.innerHTML="<p class='image'><a href='"+data[i].href+"'><img src='"+data[i].src+"'></a></p><p class='caption'>長さ"+data[i].width+"cm,　高さ"+data[i].height+"cm,　幅"+data[i].depth+"cm</p>";
            newElement.innerHTML+="<p><form name ='input_form' action='push.php' method='post'><input type='submit' value='登録' /><input type='hidden' name='hidden_input' value='"+id+","+data[i].width+","+data[i].height+","+data[i].depth+"'/></form></p>";
            document.body.appendChild(newElement);
          }
        });
      },
      error:function(xhr,textStatus,error){
        console.log("error");
      }
    }); 
}
function bed(){
  $.ajax({
    type:'GET',
    url:'./bedJSON.php',
    dataType:'json',
    success:function(data){
      console.log("success");
      console.log(data);
      $(function(){
        $('.result').remove();
        for(var i=0;i<data.length;i++){
         var newElement=document.createElement("div"); 
         newElement.className='result';
         var id=2;
            newElement.innerHTML="<p class='image'><a href='"+data[i].href+"'><img src='"+data[i].src+"'></a></p><p class='caption'>長さ"+data[i].width+"cm,　高さ"+data[i].height+"cm,　幅"+data[i].depth+"cm</p>";
            newElement.innerHTML+="<p><form name ='input_form' action='push.php' method='post'><input type='submit' value='登録' /><input type='hidden' name='hidden_input' value='"+id+","+data[i].width+","+data[i].height+","+data[i].depth+"'/></form></p>";
            document.body.appendChild(newElement);
       }
     });
    },
    error:function(xhr,textStatus,error){
      console.log("error");
    }
  }); 
}
function mirror(){
  $.ajax({
    type:'GET',
    url:'./mirrorJSON.php',
    dataType:'json',
    success:function(data){
      console.log("success");
      console.log(data);
      $(function(){
        $('.result').remove();
        for(var i=0;i<data.length;i++){
         var newElement=document.createElement("div"); 
         newElement.className='result';
          var id=3;
            newElement.innerHTML="<p class='image'><a href='"+data[i].href+"'><img src='"+data[i].src+"'></a></p><p class='caption'>長さ"+data[i].width+"cm,　高さ"+data[i].height+"cm,　幅"+data[i].depth+"cm</p>";
            newElement.innerHTML+="<p><form name ='input_form' action='push.php' method='post'><input type='submit' value='登録' /><input type='hidden' name='hidden_input' value='"+id+","+data[i].width+","+data[i].height+","+data[i].depth+"'/></form></p>";
            document.body.appendChild(newElement);
       }
     });
    },
    error:function(xhr,textStatus,error){
      console.log("error");
    }
  }); 
}
function chair(){
  $.ajax({
    type:'GET',
    url:'./chairJSON.php',
    dataType:'json',
    success:function(data){
      console.log("success");
      console.log(data);
      $(function(){
        $('.result').remove();
        for(var i=0;i<data.length;i++){
         var newElement=document.createElement("div"); 
         newElement.className='result';
          var id=4;
            newElement.innerHTML="<p class='image'><a href='"+data[i].href+"'><img src='"+data[i].src+"'></a></p><p class='caption'>長さ"+data[i].width+"cm,　高さ"+data[i].height+"cm,　幅"+data[i].depth+"cm</p>";
            newElement.innerHTML+="<p><form name ='input_form' action='push.php' method='post'><input type='submit' value='登録' /><input type='hidden' name='hidden_input' value='"+id+","+data[i].width+","+data[i].height+","+data[i].depth+"'/></form></p>";
            document.body.appendChild(newElement);
       }
     });
    },
    error:function(xhr,textStatus,error){
      console.log("error");
    }
  }); 
}
function tvstand(){
  $.ajax({
    type:'GET',
    url:'./tvstandJSON.php',
    dataType:'json',
    success:function(data){
      console.log("success");
      console.log(data);
      $(function(){
        $('.result').remove();
        for(var i=0;i<data.length;i++){
         var newElement=document.createElement("div"); 
         newElement.className='result';
 var id=5;
            newElement.innerHTML="<p class='image'><a href='"+data[i].href+"'><img src='"+data[i].src+"'></a></p><p class='caption'>長さ"+data[i].width+"cm,　高さ"+data[i].height+"cm,　幅"+data[i].depth+"cm</p>";
            newElement.innerHTML+="<p><form name ='input_form' action='push.php' method='post'><input type='submit' value='登録' /><input type='hidden' name='hidden_input' value='"+id+","+data[i].width+","+data[i].height+","+data[i].depth+"'/></form></p>";
            document.body.appendChild(newElement);
       }
     });
    },
    error:function(xhr,textStatus,error){
      console.log("error");
    }
  }); 
}
function chest(){
  $.ajax({
    type:'GET',
    url:'./chestJSON.php',
    dataType:'json',
    success:function(data){
      console.log("success");
      console.log(data);
      $(function(){
        $('.result').remove();
        for(var i=0;i<data.length;i++){
         var newElement=document.createElement("div"); 
         newElement.className='result';
          var id=6;
            newElement.innerHTML="<p class='image'><a href='"+data[i].href+"'><img src='"+data[i].src+"'></a></p><p class='caption'>長さ"+data[i].width+"cm,　高さ"+data[i].height+"cm,　幅"+data[i].depth+"cm</p>";
            newElement.innerHTML+="<p><form name ='input_form' action='push.php' method='post'><input type='submit' value='登録' /><input type='hidden' name='hidden_input' value='"+id+","+data[i].width+","+data[i].height+","+data[i].depth+"'/></form></p>";
            document.body.appendChild(newElement);
       }
     });
    },
    error:function(xhr,textStatus,error){
      console.log("error");
    }
  }); 
}
function sofa(){
  $.ajax({
    type:'GET',
    url:'./sofaJSON.php',
    dataType:'json',
    success:function(data){
      console.log("success");
      console.log(data);
      $(function(){
        $('.result').remove();
        for(var i=0;i<data.length;i++){  
          var newElement=document.createElement("div"); 
          newElement.className='result';
 var id=7;
            newElement.innerHTML="<p class='image'><a href='"+data[i].href+"'><img src='"+data[i].src+"'></a></p><p class='caption'>長さ"+data[i].width+"cm,　高さ"+data[i].height+"cm,　幅"+data[i].depth+"cm</p>";
            newElement.innerHTML+="<p><form name ='input_form' action='push.php' method='post'><input type='submit' value='登録' /><input type='hidden' name='hidden_input' value='"+id+","+data[i].width+","+data[i].height+","+data[i].depth+"'/></form></p>";
            document.body.appendChild(newElement);
        }
      });
    },
    error:function(xhr,textStatus,error){
      console.log("error");
    }
  }); 
}
</script>
<style type="text/css">
  div.result{
    width:300px;
    float:left;
  }
  p.image,p.caption{
    text-align: center;
  }
</style>
</head>
<body>
  <div id="button">
    <input type="button" onclick="table();"value="table">
    <input type="button" onclick="bed();"value="bed"/>
    <input type="button" onclick="mirror();"value="mirror"/>
    <input type="button" onclick="chair();"value="chair"/>
    <input type="button" onclick="tvstand();"value="tvstand"/>
    <input type="button" onclick="chest();"value="chest"/>
  </div>
  <div class="inner"></div>
</body>
</html>
