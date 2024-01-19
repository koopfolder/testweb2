<template>
  <fragment>
  <div class="bg_book" ref="homeCont">
        <h1>{{ title }}</h1>
        <div class="owl-book owl-carousel owl-theme" >
            <div v-for="(data,index) of data">
                <a v-bind:href="data.link" target="_blank">
                <div class="bookimg">
                    <img v-bind:src="data.cover_desktop"  v-bind:alt="data.title" class="img-responsive">
                </div>
                <div class="wrap_textbook">
                    <p class="dotmaster">
                    {{ data.title }}
                    </p>
                </div>
                </a>
            </div>
        </div>   
  </div>       
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
          access_token:{},
          path_owl_left_direction_arrow:{},
          path_owl_right_thin_chevron:{},
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
                    $('.owl-book').on('initialized.owl.carousel changed.owl.carousel', function(event){ 
                        $(".dotmaster").trigger("update.dot");
                    });
                    $(".owl-book").owlCarousel({
                        loop:true,
                        margin:0,
                        nav:true,
                        dots:false,
                        autoplay:true,
                        autoplayTimeout:6000,       
                        navText: ["<img src='"+this.path_owl_left_direction_arrow+"'>","<img src='"+this.path_owl_right_thin_chevron+"'>"],
                        slideBy: 1,
                        responsive:{
                            0:{
                                items:1,
                                margin:0
                                //slideBy: 3
                            },
                            500:{
                                items:1
                            },
                            768:{
                                margin:0,
                                items:1
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
            //console.log("Dot Master");
            $('.dotmaster').dotdotdot({
                watch: 'window'
            });
          }
        }
    }

</script>
