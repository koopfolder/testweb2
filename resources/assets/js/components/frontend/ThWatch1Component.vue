<template>
  <fragment>
    <section class="row wow fadeInDown">
        <div class="col-xs-12 banner-slide"  ref="homeCont" >
            <div class="banner-carousel owl-carousel owl-theme">
                <figure class="banner-img" v-for="(data,index) of data">
                    <a v-bind:href="data.link">
                        <img v-bind:src="data.img" v-bind:alt="data.name">
                    </a>
                </figure>
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
                    $(".banner-carousel").on('initialized.owl.carousel', function(event){ 
                        //$('.item_banner').css('height', $(window).height());
                    }).owlCarousel({
                        loop:true,
                        //rewind: true,
                        margin:0,
                        nav:true,
                        navText: ['<span><i class="fas fa-chevron-left"></i></span>','<span><i class="fas fa-chevron-right"></i></span>'],
                        autoplayHoverPause: false,
                        dots:false,
                        autoplay:true,
                        autoplayTimeout:10000,
                        slideSpeed: 2500,
                        paginationSpeed: 500,
                        smartSpeed: 1000,
                        stagePadding: 0,
                        slideBy: 1,
                        responsive:{
                            0:{
                                items:1
                            },
                            500:{
                                items:1
                            },
                            768:{
                                items:1
                            },
                            1201:{
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
            console.log("Dot Master");
            $('.dotmaster').dotdotdot({
                watch: 'window'
            });
          }
        }
    }

</script>
