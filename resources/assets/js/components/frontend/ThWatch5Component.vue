<template>
  <fragment>
    <section class="row video-fp wow fadeInDown" ref="homeCont" >
        <span></span>
        <div class="container">
            <div class="row">
                <div class="col-xs-12" v-html="html">

                    <!--<a href="https://www.youtube.com/watch?v=S3XIDTHYKOw" class="video-fp-link" data-fancybox>
                        <img src="http://thrc.hap.com/themes/thrc/thaihealth-watch/images/Path 3292.png" alt="">
                        <div class="video-fp-caption">
                            <img src="http://thrc.hap.com/themes/thrc/thaihealth-watch/images/icon-play.svg" alt="">
                            <h2>Thaihealth Watch 2021</h2>
                            <p>มาร่วมจับตาทิศทางสุขภาพคนไทยไปด้วยกัน “ThaiHealth Watch 2021 : Rewind the Future”</p>
                        </div>
                    </a>-->

                    <!-- 
                    <a data-fancybox href="#myVideo"> </a> 
                    <video width="640" height="320" controls id="myVideo" style="display:none;">
                      <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.mp4" type="video/mp4">
                      <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.webm" type="video/webm">
                      <source src="https://www.html5rocks.com/en/tutorials/video/basics/Chrome_ImF.ogv" type="video/ogg">
                      Your browser doesn't support HTML5 video tag.
                    </video>                    
                    -->

                </div>
            </div>
        </div>
    </section>    
  </fragment>
</template>
<script>
    export default {
        mounted() {
          //console.log(this.api_url,this.access_token);
          this.api_data;
        },
        async created (){
            //await this.api_data;
            //await this.dotmaster
        },
        data() {
          return {
                  data:[],
                  html:'',
                  fullPage: false
                 } 
        },
        props: {
          api_url:{},
          access_token:{},
          text_read_more:{},
        },
        methods: {

        },
        computed:{
          api_data:function(){
            let loader = this.$loading.show({
              container: this.fullPage ? null : this.$refs.homeCont,
              canCancel: false,
              loader:'dots',
              color:'#ef7e25'
            });
            //console.log(this.fullPage,this.$refs.homeCont);
            const headers = {
              'Content-Type': 'application/json; charset=utf-8',
              'authorization': 'Bearer '+this.access_token
            }

            axios.post(this.api_url,{},{
                headers:headers
            })
            .then(response=>{
                //console.log(response.data);
                if(response.status ==200){ 
                  loader.hide();
                  this.data = response.data.val;
                  this.html = response.data.val.video_path;
                  setTimeout(function(){  

                  }, 2000);
                }
                //console.log(this.most_viewed_statistic_data);
            })
            .catch(function (error) {
              // handle error
              console.log(error);
            })
            .then(function () {
              // always executed
            });
            


          },
          dotmaster:function(){
            console.log("Dot Master");
            $('.dotmaster').dotdotdot({
                watch: 'window'
            });
          }
        }
    }

</script>
