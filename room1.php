<?php

	if(isset($_GET["roomX"])){
		//---変数--------------------

		//部屋の縦・横・高さ
		$room_X = $_GET["roomX"];
		$room_Y = $_GET["roomY"];
		$room_Z = $_GET["roomZ"];
	
		//gridの一辺の長さと一マスの大きさ
		if($room_X>$room_Z){
			$grid_side = $room_X;
		}else{
			$grid_side = $room_Z;
		}
		$math_one = $grid_side/10;
	
		//壁の座標
		$position_X = -$room_X/2;
		$position_Y = $room_Y/2;
		$position_Z = -$room_Z/2;
		//--------------------------
	}
	else{

	}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<title>cmp-group</title>
	<meta charset="utf-8">
</head>

<body>
	<script src="lib/three.min.js"></script>
	<script src="lib/DDSLoader.js"></script>
	<script src="lib/MTLLoader.js"></script>
	<script src="lib/OBJMTLLoader.js"></script>
	<script src="lib/Detector.js"></script>
	<script src="lib/stats.min.js"></script>
	<script src="lib/TrackballControls.js"></script>
	<script type="text/javascript" src="lib/jquery-2.1.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
	<script type="text/javascript">

		//変数
		var container, stats;
		var camera, controls, scene, renderer;
		var objects = [], plane, number, select;
		var models = [];
		var touch = [];
		var visible = 0;

		var mouse = new THREE.Vector2(),
		offset = new THREE.Vector3(),
		INTERSECTED, SELECTED;

		var windowX = window.innerWidth;
		var windowY = window.innerHeight;
		var window_X = windowX * 0.6;
		var window_Y = windowY * 0.7;
		  
		//カテゴリー		
		var category = [];
		
		//オブジェクトのサイズ
		var width = [];
		var height = [];
		var depth = [];

		//オブジェクトの座標
		var posX = [];
		var posZ = [];

		//オブジェクトの向き
		var direction = [];

		//データベースから読み込む
	    <?php
			$db = new PDO("sqlite:furniture.sqlite");
			$rows = $db->query( "SELECT * from furniture"); 
			$count = 0;
			while($all = $rows->fetch()){
				print 'category['.$count.']='.$all["category"].';';
				print 'width['.$count.']='.$all["width"].';';
    	    	print 'depth['.$count.']='.$all["depth"].';';
        		print 'height['.$count.']='.$all["height"].';';
        		print 'posX['.$count.']='.$all["posX"].';';
        		print 'posZ['.$count.']='.$all["posZ"].';';
        		print 'direction['.$count.']='.$all["direction"].';';
        		//print 'number='.$count.';';
				print 'console.log(category['.$count.']);';
				$count++;
			}
			print 'number='.$count.';';
			print 'console.log(number);';
		?>

		init();
		animate();

		function init() {
			container = document.createElement( 'div' );
			container.style.float = 'left';

			document.body.appendChild( container );

			/*
			//style
			var infoLoad = document.createElement( 'div' );
			infoLoad.style.position = 'absolute';
			infoLoad.style.top = '10px';
			infoLoad.style.width = '100%';
			infoLoad.style.textAlign = 'center';
			infoLoad.innerHTML = '<a href="javascript:load()">load</a>';
			container.appendChild( infoLoad );

			/style
			var infoSave = document.createElement( 'div' );
			infoSave.style.position = 'absolute';
			infoSave.style.top = '20px';
			infoSave.style.width = '100%';
			infoSave.style.textAlign = 'center';
			infoSave.innerHTML = '<a href="javascript:save()">save</a>';
			container.appendChild( infoSave );
			*/

			camera = new THREE.PerspectiveCamera( 70, window_X / window_Y, 1, 10000 );
			camera.position.z = 1000;

			controls = new THREE.TrackballControls( camera );
			controls.rotateSpeed = 1.0;
			controls.zoomSpeed = 1.2;
			controls.panSpeed = 0.8;
			controls.noZoom = false;
			controls.noPan = false;
			controls.staticMoving = true;
			controls.dynamicDampingFactor = 0.3;

			//　scene
			scene = new THREE.Scene();

			//light
			var ambient = new THREE.AmbientLight( 0x444444 );
			scene.add( ambient );

			var directionalLight = new THREE.DirectionalLight( 0xffeedd );
			directionalLight.position.set( 0, 0, 1 ).normalize();
			scene.add( directionalLight );

			//floor-wall
			var material = new THREE.MeshLambertMaterial( { ambient: 0xbbbbbb } );

			var room_X = <?php echo $room_X ?>;
			var room_Y = <?php echo $room_Y ?>;
			var room_Z = <?php echo $room_Z ?>;

			var grid_side = <?php echo $grid_side ?>/2;
			var math_one = <?php echo $math_one ?>;

			var position_X = <?php echo $position_X ?>;
			var position_Y = <?php echo $position_Y ?>;
			var position_Z = <?php echo $position_Z ?>;

			//Grid(大きさ, １マスの大きさ)
			var grid = new THREE.GridHelper( grid_side, 100);
			scene.add( grid );

			object = new THREE.Mesh( new THREE.PlaneGeometry( room_X, room_Y), material );
			object.position.set( 0, position_Y, position_Z);
			scene.add( object );
			object = new THREE.Mesh( new THREE.PlaneGeometry( room_Z, room_Y), material );
			object.position.set( position_X, position_Y, 0 );
			object.rotateY( Math.PI / 2 );
			scene.add( object );

    		//box配置  
			for ( var i = 0; i < number+1; i ++ ) {
		  		var geometry = new THREE.BoxGeometry( width[i], height[i], depth[i] );
				var object = new THREE.Mesh( geometry, new THREE.MeshLambertMaterial( { color: 0xffffff, transparent: true, opacity: 0.5 } ) )
		    	object.position.x = posX[i];
				object.position.z = posZ[i];
    			object.position.y = height[i]/2;
    			object.visible = false;
				scene.add( object );
				objects.push( object );
			}

			//model読み込み
        	for ( var i = 0; i < number; i++ ) {
        		loadModel(i);
       		}
       		

			//当たり判定
    		collision();

    		//plane
    		plane = new THREE.Mesh(
				new THREE.PlaneBufferGeometry( 5000, 5000, 8, 8 ),
				new THREE.MeshBasicMaterial( { color: 0x000000, opacity: 0.25, transparent: true } )
			);
			plane.visible = false;
			scene.add( plane );

			//renderer
			renderer = new THREE.WebGLRenderer( { antialias: true } );
			renderer.sortObjects = false;
			renderer.setSize( window_X, window_Y);
			container.appendChild( renderer.domElement );
		    
			renderer.domElement.addEventListener( 'mousemove', onDocumentMouseMove, false );
			renderer.domElement.addEventListener( 'mousedown', onDocumentMouseDown, false );
			renderer.domElement.addEventListener( 'mouseup', onDocumentMouseUp, false );
		   
			window.addEventListener( 'resize', onWindowResize, false );
		}

		//model読み込み
		function loadModel(n){  
			var onProgress = function ( xhr ) {
				if ( xhr.lengthComputable ) {
					var percentComplete = xhr.loaded / xhr.total * 100;
					console.log( Math.round(percentComplete, 2) + '% downloaded' );
				}	
			};
	  		var onError = function ( xhr ) {};

			THREE.Loader.Handlers.add( /\.dds$/i, new THREE.DDSLoader() );

			//追加
        	if( category[n]===1 ){
        		var loader1 = new THREE.OBJMTLLoader();
       			loader1.load( 'obj/table.obj', 'obj/table.mtl', function ( object ) {
       			scene.add( object );
       			object.visible = false;
      			models[n]=object;    
     			}, onProgress, onError);
   		    }else if( category[n]===2 ){
   		    	var loader2 = new THREE.OBJMTLLoader();
       			loader2.load( 'obj/bed.obj', 'obj/bed.mtl', function ( object ) {
       			scene.add( object );
       			object.visible = false;
      			models[n]=object;    
     			}, onProgress, onError);
     		 }else if( category[n]===3 ){
   		    	var loader3 = new THREE.OBJMTLLoader();
       			loader3.load( 'obj/table.obj', 'obj/table.mtl', function ( object ) {
       			scene.add( object );
       			object.visible = false;
      			models[n]=object;    
     			}, onProgress, onError);
   		    }else if( category[n]===4 ){
   		    	var loader3 = new THREE.OBJMTLLoader();
       			loader3.load( 'obj/chair.obj', 'obj/chair.mtl', function ( object ) {
       			scene.add( object );
       			object.visible = false;
      			models[n]=object;    
     			}, onProgress, onError);
   		    }else if( category[n]===5 ){
   		    	var loader4 = new THREE.OBJMTLLoader();
       			loader4.load( 'obj/tvstand.obj', 'obj/tvstand.mtl', function ( object ) {
       			scene.add( object );
       			object.visible = false;
      			models[n]=object;    
     			}, onProgress, onError);
   		    }else if( category[n]===6 ){
   		    	var loader5 = new THREE.OBJMTLLoader();
       			loader5.load( 'obj/bookshelf.obj', 'obj/bookshelf.mtl', function ( object ) {
       			scene.add( object );
       			object.visible = false;
      			models[n]=object;    
     			}, onProgress, onError);
   		    }else if( category[n]===7 ){
   		    	var loader6 = new THREE.OBJMTLLoader();
       			loader6.load( 'obj/sofa.obj', 'obj/sofa.mtl', function ( object ) {
       			scene.add( object );
       			object.visible = false;
      			models[n]=object;    
     			}, onProgress, onError);
   		    }
   		}

		//当たり判定を行う関数
		var touch = [];
		function collision() {
			for( var j=0; j<objects.length; j++ ){
				touch[j]=0;
				for( var i=0; i<objects.length; i++ ){
					if( i != j ){
						if( direction[i]===0 || direction[i]===2 ){
							if ( objects[j].position.x-width[j]/2 <= objects[i].position.x+width[i]/2
								&& objects[j].position.x+width[j]/2 >= objects[i].position.x-width[i]/2 
								&& objects[j].position.z-depth[j]/2 <= objects[i].position.z+depth[i]/2
								&& objects[j].position.z+depth[j]/2 >= objects[i].position.z-depth[i]/2
								){
									touch[j]=1;
							}
						}else{
							if ( objects[j].position.x-depth[j]/2 <= objects[i].position.x+depth[i]/2
								&& objects[j].position.x+depth[j]/2 >= objects[i].position.x-depth[i]/2 
								&& objects[j].position.z-width[j]/2 <= objects[i].position.z+width[i]/2
								&& objects[j].position.z+width[j]/2 >= objects[i].position.z-width[i]/2
								){
									touch[j]=1;
							}
						}
					}
				}
				if( touch[j]==1 ) console.log("touch");
		    		if( touch[j]==1 ) {
		    			objects[j].material.color.setHex(0xD71D3B);
		    			if( visible==1 ){
		    			//objects[j].visible = true;
		    			}
		    		}
		    		else{
		    			objects[j].material.color.setHex(0xffffff);
		    			//objects[j].visible = false;
		    		}
			}
		}
		  	//ウィンドウサイズ変更時
		function onWindowResize() {
			camera.aspect = window_X/ window_Y;
			camera.updateProjectionMatrix();
			renderer.setSize( window_X, window_Y);
			render();
		}

		function onDocumentMouseMove( event ) {

			var room_X = <?php echo $room_X ?>;
			var room_Y = <?php echo $room_Y ?>;
			var room_Z = <?php echo $room_Z ?>;

			event.preventDefault();

			mouse.x = ( event.clientX / window_X) * 2 - 1;
			mouse.y = - ( event.clientY / window_Y) * 2 + 1;

			//

			var vector = new THREE.Vector3( mouse.x, mouse.y, 0.5 ).unproject( camera );
			var raycaster = new THREE.Raycaster( camera.position, vector.sub( camera.position ).normalize() );

		if ( SELECTED ) {
				var intersects = raycaster.intersectObject( plane );
				SELECTED.position.copy( intersects[ 0 ].point.sub( offset ) );

				//y座標の調節
				SELECTED.position.y = height[select]/2;          
				//当たり判定
				//壁
				if( direction[select]===0 || direction[select]===2 ){
					if ( SELECTED.position.x < -room_X/2+width[select]/2 ) SELECTED.position.x = -room_X/2+width[select]/2;
		  		if ( SELECTED.position.x > room_X/2-width[select]/2 ) SELECTED.position.x = room_X/2-width[select]/2;
		  		if ( SELECTED.position.z < -room_Z/2+depth[select]/2 ) SELECTED.position.z = -room_Z/2+depth[select]/2;
					if ( SELECTED.position.z > room_Z/2-depth[select]/2 ) SELECTED.position.z = room_Z/2-depth[select]/2;
				}else{	
					if ( SELECTED.position.x < -room_X/2+depth[select]/2 ) SELECTED.position.x = -room_X/2+depth[select]/2;
		  		if ( SELECTED.position.x > room_X/2-depth[select]/2 ) SELECTED.position.x = room_X/2-depth[select]/2;
		  		if ( SELECTED.position.z < -room_Z/2+width[select]/2 ) SELECTED.position.z = -room_Z/2+width[select]/2;
					if ( SELECTED.position.z > room_Z/2-width[select]/2 ) SELECTED.position.z = room_Z/2-width[select]/2;
				}
				//modelの座標
				models[select].position.x = SELECTED.position.x; 
				models[select].position.y = SELECTED.position.y; 
				models[select].position.z = SELECTED.position.z;

				//他の物体との当たり判定
				collision();
				return;
			}

			var intersects = raycaster.intersectObjects( objects );
			if ( intersects.length > 0 ) {
				if ( INTERSECTED != intersects[ 0 ].object ) {
					if ( INTERSECTED ) INTERSECTED.material.color.setHex( INTERSECTED.currentHex );
					INTERSECTED = intersects[ 0 ].object;
					INTERSECTED.currentHex = INTERSECTED.material.color.getHex();
					plane.position.copy( INTERSECTED.position );
					plane.lookAt( camera.position );
				}
				container.style.cursor = 'pointer';
			} else {
				if ( INTERSECTED ) INTERSECTED.material.color.setHex( INTERSECTED.currentHex );
				INTERSECTED = null;
				container.style.cursor = 'auto';
			}
			collision();
		}

		function onDocumentMouseDown( event ) {
			event.preventDefault();
			var vector = new THREE.Vector3( mouse.x, mouse.y, 0.5 ).unproject( camera );
			var raycaster = new THREE.Raycaster( camera.position, vector.sub( camera.position ).normalize() );
			var intersects = raycaster.intersectObjects( objects );
			if ( intersects.length > 0 ) {
				controls.enabled = false;
				SELECTED = intersects[ 0 ].object;

				//選択した物体の番号を記録
				for( var i=0; i<objects.length; i++ ){
					if( intersects[0].object == objects[i] ){
						select=i;
					}
				}
				var intersects = raycaster.intersectObject( plane );
				offset.copy( intersects[ 0 ].point ).sub( plane.position );
				container.style.cursor = 'move';
			}

		}

		function onDocumentMouseUp( event ) {
			event.preventDefault();
			controls.enabled = true;
			if ( INTERSECTED ) {
				plane.position.copy( INTERSECTED.position );
				SELECTED = null;
			}
			container.style.cursor = 'auto';
		}

		//回転
			document.onkeydown = function (e){
		//出力テスト
			console.log(e.keyCode);
				if (!e)	e = window.event;11
				if( e.keyCode == 65 ){   
					if( direction[select]<3 ){
						direction[select]++;
					}     
					else{
							direction[select]=0;
						}
						objects[select].rotation.y = Math.PI/2*direction[select]; 
					models[select].rotation.y = Math.PI/2*direction[select]; 
					var value = width[select];
					width[select] = depth[select];
					depth[select] = value;
					}
				};

			//load
			function load(){
				visible=1;
		  	for ( var i = 0; i < number+1; i ++ ) {
		  		objects[i].visible = true;
		 			objects[i].position.x = posX[i];
					objects[i].position.z = posZ[i];		
					objects[i].position.y = height[i]/2;
					objects[i].rotation.y = Math.PI/2*direction[i]; 
		  	}
				for ( var i = 0; i < number+1; i ++ ) {
		  		models[i].visible = true;
		   	 	models[i].scale.x = width[i];
		 			models[i].scale.z = depth[i];
					models[i].scale.y = height[i];
		 			models[i].position.x = posX[i];
					models[i].position.z = posZ[i];		
					models[i].position.y = height[i]/2;
					models[i].rotation.y = Math.PI/2*direction[i]; 
				}
				collision();
			}

		//save
		function save(){
				for ( var i = 0; i < number+1; i ++ ) {
		      		posX[i]=objects[i].position.x;
				posZ[i]=objects[i].position.z;
			}
				$.get("save.php",
		  			{ number:number, posX:posX, posZ:posZ, direction:direction },
		  			function(data){
		    			alert(data);
		  			}
				);
			}

		//アニメーション	
		function animate() {
			requestAnimationFrame( animate );
			render();
		}
		//render
		function render() {
			controls.update();
			renderer.render( scene, camera );
		}
	</script>

	<h1 style="position:absolute; left:20px; top:20px; background-color:rgba(200,200,200,0.8); margin=30px">"Selection of Furniture"</h1>
	<div id="button">
		<input type="button" id="load" value="LOAD" onClick="javascript:load()">
		<input type="button" id="save" value="SAVE" onClick="javascript:save()">
		<input type="button" id="add" value="ADD" onClick="location.href='test.php'">
	</div>
	<div id="list">
		<table border="0" cellpadding="0" cellspacing="0" align="right">
			<tr bgcolor="#aaaaff">
				<td width=10%><b>categoly</b></td>
			</tr>
			<?php
				$db = new PDO("sqlite:furniture.sqlite");
				$rows = $db->query( "SELECT * from furniture");
				for($i=0; $row=$rows->fetch(); $i++){
					$cg = $row['category'];
					if($cg == 1){
						echo "<tr><td>テーブル</td></tr>";
					}
					else if($cg==2){
						echo "<tr><td>ベット</td></tr>";
					}
					else if($cg==3){
						echo "<tr><td>ミラー</td></tr>";
					}
					else if($cg==4){
						echo "<tr><td>チェアー</td></tr>";
					}
					else if($cg==5){
						echo "<tr><td>TVスタンド</td></tr>";
					}
					else if($cg==6){
						echo "<tr><td>チェスト</td></tr>";
					}
					else if($cg==7){
						echo "<tr><td>ソファ-</td></tr>";
					}
				}
			?>
			
		</table>
	</div>
	<div id="scale">
		<form method="GET" action="room1.php">
			<fieldset>
				<legend>部屋の大きさ</legend>
				縦　：　
				<select name="roomX">
					<option value="500">　５</option>
					<option value="600">　６</option>
					<option value="700">　７</option>
					<option value="800">　８</option>
					<option value="900">　９</option>
					<option value="1000">１０</option>
					<option value="1500">１５</option>
					<option value="2000">２０</option>
				</select>
				ｍ
				横　：　 
				<select name="roomZ">
					<option value="500">　５</option>
					<option value="600">　６</option>
					<option value="700">　７</option>
					<option value="800">　８</option>
					<option value="900">　９</option>
					<option value="1000">１０</option>
					<option value="1500">１５</option>
					<option value="2000">２０</option>
				</select>
				ｍ
				高さ　：　 
				<select name="roomY">
					<option value="200">　　　２</option>
					<option value="250">　２．５</option>
					<option value="300">　　　３</option>
					<option value="350">　３．５</option>
					<option value="400">　　　４</option>
					<option value="450">　４．５</option>
					<option value="500">　　　５</option>
				</select>
				ｍ	
			<input type="submit" value="送信">
			</fieldset>
		</form>
	</div>
</body>
</html>