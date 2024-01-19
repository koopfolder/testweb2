<template>
  <fragment>
    <section class="row wow fadeInDown" ref="homeCont">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_news">
                    <h1>{{ title }}</h1>
                    <a v-bind:href="url_list">{{ text_read_more }}</a>
                </div>
            </div>
            <div class="row row_trend row_trend_fp">
                <div class="col-xs-12 col-sm-4 col-md-4 item_trend" v-for="(data,index) of data">
                    <a v-bind:href="data.url">
                        <figure>
                            <div>
                                <img v-bind:src="data.img" v-bind:alt="data.title">
                            </div>
                            <figcaption>
                                <h2>{{ data.title }}</h2>
                            </figcaption>
                        </figure>
                    </a>
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
          title:{},
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
