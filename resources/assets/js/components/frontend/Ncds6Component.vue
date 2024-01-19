<template>
  <fragment>
    <section class="row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="head_ncds">
                        <h1>{{ text_title }}</h1>
                        <a v-bind:href="url_view_all">{{ text_view_all }}</a>
                    </div>
                </div>
            </div>
            <div class="row row_ncdsupdate" ref="homeCont" style="position: relative;">
                <div class="col-12 col-md-6 item_ncdsupdate_hl">
                    <a v-bind:href="data_main.url" v-if="data_main !=''">
                        <figure>
                            <div><img v-bind:src="data_main.img" v-bind:alt="data_main.title"></div>
                            <figcaption>
                                <h3 class="dotmaster">{{ data_main.title }}</h3>
                                <p class="dotmaster">{{ data_main.description }}</p>
                                <div class="ncds_dateview">{{ data_main.created_at }} <img v-bind:src="icon_ncds_eye"> {{ data_main.hit }}</div>
                            </figcaption>
                        </figure>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <div class="row row_ncdsupdate">

                        <div class="col-6 item_ncdsupdate" v-for="(data,index) of data">
                            <a v-bind:href="data.url">
                                <figure>
                                    <div><img v-bind:src="data.img" v-bind:alt="data.title"></div>
                                    <figcaption>
                                        <h3 class="dotmaster">{{ data.title }}</h3>
                                        <p class="dotmaster">{{ data.description }}</p>
                                        <div class="ncds_dateview">{{ data.created_at }} <img v-bind:src="icon_ncds_eye"> {{ data.hit }}</div>
                                    </figcaption>
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
                  data_main:'',
                  data:[],
                  fullPage: false
                 } 
        },
        props: {
          api_url:{},
          access_token:{},
          text_title:{},
          text_view_all:{},
          url_view_all:{},
          icon_ncds_eye:{},
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
                  this.data_main = response.data.val_main;
                  this.data = response.data.val;
                  //console.log(this.data_main,this.data);
                  setTimeout(function(){  
                    $('.dotmaster').dotdotdot({
                        watch: 'window'
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
