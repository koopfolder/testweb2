<template>
  <fragment>
    <section class="row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="head_ncds head_ncdsnombot">
                        <h1>{{ text_title }}</h1>
                    </div>
                </div>
            </div>
            <div class="row" ref="homeCont" style="position: relative;">
                <div class="col-12 associate_slide">
                    
                    <div class="owl-carousel owl-theme associate-carousel">
                        <a v-bind:href="data.link" class="item_associate" v-for="(data,index) of data">
                            <img v-bind:src="data.img" v-bind:alt="data.name">
                        </a>
                    </div>
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
                  fullPage: false
                 } 
        },
        props: {
          api_url:{},
          access_token:{},
          text_title:{},
          icon_owl_left_direction_arrow_ncds_w:{},
          icon_owl_right_thin_chevron_ncds_w:{}
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

                  let icon_owl_left_direction_arrow_ncds_w = this.icon_owl_left_direction_arrow_ncds_w;
                  let icon_owl_right_thin_chevron_ncds_w = this.icon_owl_right_thin_chevron_ncds_w;
                 setTimeout(function(){  
                    $('.associate-carousel').owlCarousel({
                        loop: true,
                        nav:true,
                        navText: ['<img src="'+icon_owl_left_direction_arrow_ncds_w+'">','<img src="'+icon_owl_right_thin_chevron_ncds_w+'">'],
                        autoplayHoverPause: true,
                        dots:false,
                        autoplay:true,
                        autoplayTimeout:6000,
                        smartSpeed: 800,
                        stagePadding: 0,
                        slideBy: 1,
                        responsive:{
                            0:{
                                margin:20,
                                items:2
                            },
                            500:{
                                margin:20,
                                items:2
                            },
                            768:{
                                margin:20,
                                items:3
                            },
                            1199:{
                                margin:20,
                                items:4
                            }
                        }
                    });
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
