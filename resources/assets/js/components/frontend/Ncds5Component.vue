<template>
  <fragment>
    <section class="row">
        <div class="container" ref="homeCont" style="position: relative;">
            <div class="row">
                <div class="col-12">
                    <div class="head_ncds">
                        <h1>{{ text_title }}</h1>
                        <a v-bind:href="url_view_all">{{ text_view_all }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="owl-carousel owl-theme tool-carousel">
                        <div class="item_tool" v-for="(data,index) of data">
                            <a v-bind:href="data.url">
                                <figure>
                                    <div><img v-bind:src="data.img" v-bind:alt="data.title"></div>
                                    <figcaption>{{ data.title }}</figcaption>
                                </figure>
                            </a>
                        </div>
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
          text_view_all:{},
          icon_owl_left_direction_arrow_ncds:{},
          icon_owl_right_thin_chevron_ncds:{},
          url_view_all:{},
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
                  let icon_owl_left_direction_arrow_ncds = this.icon_owl_left_direction_arrow_ncds;
                  let icon_owl_right_thin_chevron_ncds = this.icon_owl_right_thin_chevron_ncds;
                  setTimeout(function(){  
                        //console.log(icon_owl_left_direction_arrow_ncds);
                        $('.tool-carousel').owlCarousel({
                            loop: true,
                            nav:true,
                            navText: ['<img src="'+icon_owl_left_direction_arrow_ncds+'">','<img src="'+icon_owl_right_thin_chevron_ncds+'">'],
                            autoplayHoverPause: true,
                            dots:false,
                            autoplay:true,
                            autoplayTimeout:6000,
                            smartSpeed: 800,
                            stagePadding: 0,
                            slideBy: 1,
                            responsive:{
                                0:{
                                    margin:15,
                                    items:2
                                },
                                500:{
                                    margin:15,
                                    items:2
                                },
                                768:{
                                    margin:15,
                                    items:3
                                },
                                1199:{
                                    margin:15,
                                    items:5
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
