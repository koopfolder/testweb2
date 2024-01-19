<template>
  <fragment>
    <section class="row wow fadeInDown" ref="homeCont" >
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_news">
                    <h1>{{ title }}</h1>
                </div>
            </div>
        </div>
        <div class="col-12 bg-agency">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 wrap-agency">
                            <div class="agency-carousel owl-carousel owl-theme">
                                <a v-bind:href="data.link" class="item_agency" v-for="(data,index) of data">
                                    <img v-bind:src="data.img" v-bind:alt="data.name">
                                </a>
                            </div>
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
          title:{},
          api_url:{},
          category_id:{},
          access_token:{},
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
                  setTimeout(function(){  
                    $(".agency-carousel").owlCarousel({
                        loop:true,
                        //rewind: true,
                        margin:15,
                        nav:true,
                        navText: ['<span><i class="fas fa-chevron-left"></i></span>','<span><i class="fas fa-chevron-right"></i></span>'],
                        autoplayHoverPause: false,
                        dots:false,
                        autoplay:true,
                        autoplayTimeout:6000,
                        smartSpeed: 1000,
                        stagePadding: 0,
                        slideBy: 1,
                        responsive:{
                            0:{
                                items:1
                            },
                            500:{
                                items:2
                            },
                            768:{
                                items:3
                            },
                            1201:{
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
