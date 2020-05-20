            jQuery(document).ready(function(){
                    jQuery("div.ngrcard:eq(0)").css("display","flex");
                    var totalDivs = jQuery("div .ngrcard").length;
                    var currentDiv = 0;
                    var setSpeed = 3000;
                    var chainInterval = setInterval(showChain, setSpeed);
                   
                    function showChain() {
                        if (currentDiv < totalDivs) {
                          jQuery("div.ngrcard").css("display","none");
                            jQuery("div.ngrcard:eq(" + currentDiv + ")").css("display","flex");
                            currentDiv++;
                        } else {
                            currentDiv = 0;
                        }
                    }
            });