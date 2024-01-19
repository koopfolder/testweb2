<template>
  <fragment>
    <section class="row wow fadeInDown" ref="homeCont">
        <div class="container">
            <div class="row row-interest-video">
                <div class="col-xs-12 col-md-6">
                    <div class="head_news">
                        <h1>{{ title }}</h1>
                        <a v-bind:href="url_list">{{ text_read_more }}</a>
                    </div>
                    <div class="row row_interest">
                        <div class="col-xs-12 col-sm-6 item_interest" v-for="(data,index) of data">
                            <a v-bind:href="data.url">
                                <figure>
                                    <div>
                                        <img v-bind:src="data.img" v-bind:alt="data.title">
                                    </div>
                                    <figcaption>
                                        <h3>{{ data.title }}</h3>
                                        <p>{{ data.description }}</p>
                                        <div class="dateandview">{{ data.created_at }}<i class="fas fa-eye"></i>{{ data.hit }}</div>
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 wrappervideofpb">
                    <div class="head_news">
                        <h1>{{ title2 }}</h1>
                        <a v-bind:href="url_list">{{ text_read_more }}</a>
                    </div>
                    <div class="wrap_video_fpb" v-html="data_video"></div>
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
                  data_video:'',
                  fullPage: false
                 } 
        },
        props: {
          api_url:{},
          title:{},
          title2:{},
          url_list:{},
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
                  this.data_video = response.data.val_video;
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
