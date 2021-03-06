

<style>

 /**
 * This work is licensed under the Creative Commons
 * Attribution 3.0 Unported License. To view a copy
 * of this license, visit http://creativecommons.org/licenses/by/3.0/.
 *
 * Author: Girish Sharma <scrapmachines@gmail.com>
 */

/* Demo specific styles begin */
 
.loader {
	
  top: 50%;
}


.loader2{
	
		
	width:100% ; 
	height:100% ; 
	background-color:#003399 ; 
	position:absolute ; 
	z-index:111111 ; 
	
	
} 



/* Demo specific styles end */

/* Loader with three blocks */
.loader, .loader:before, .loader:after {
  display: inline-block;
  width: 100%;
  height: 10px;
  position: absolute;
  z-index: 100000;
  animation: loading 4s cubic-bezier(.1,.85,.9,.15) infinite, loading-opacity 2s ease-in-out infinite alternate;
  background: linear-gradient(to right, #FFF 0px, #FFF 10px, transparent 10px)  no-repeat 0px 0px / 10px 10px;
  content: ' ';
}
.loader {
  animation-delay: .1s;
}
.loader:after {
  animation-delay: .2s;
}
@keyframes loading-opacity {
	0% {
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    opacity: 1;
  }
}
@keyframes loading {
	0% {
    background-position: -50% 0px;
  }
  100% {
    background-position: 150% 0px;
  }
}

/* 2 more loading blocks */
.loader.more:before, .loader.more:after {
  content: ' ■';
  color: #FFF;
  top: 0;
  line-height: 6px;
  font-size: 22px;
  font-family: "Times New Roman";
  vertical-align: top;
  animation: loading 4s cubic-bezier(.1,.85,.9,.15) infinite, loading-font 4s cubic-bezier(.1,.85,.9,.15) infinite !important;
}
.loader.more:before {
  animation-delay: 0s,.2s !important;
}
.loader.more {
  overflow: hidden;
  opacity: 0;
  animation: loading 4s cubic-bezier(.1,.85,.9,.15) infinite, loading-opacity 2s ease-in-out infinite alternate;
  animation-delay: .6s,.4s !important;
}
.loader.more:after {
  animation-delay: .4s,.8s !important;
}
@keyframes loading-font {
	0% {
    text-indent: calc(-50% - 5px);
  }
  100% {
    text-indent: calc(150% - 10px);
  }
}



.hidden {
  opacity: 0;
}




</style>


<div class="loader2" id="loader2" > 
<div class="loader more" >
</div>


</div> 


