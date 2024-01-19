<template>
  <fragment>
    <section class="row" ref="homeCont" >
        <div class="container">
            <div class="row row-about">
                <div class="col-xs-12 col-md-6 about-thwatch wow fadeInLeft">
                    <h1>{{ data.title }}</h1>
                    <div v-html="html"></div>
                    <a v-bind:href="data.url">{{ text_read_more }}</a>
                </div>
                <div class="col-xs-12 col-md-6 about-img wow fadeInRight">
                    <div><img v-bind:src="data.img" v-bind:alt="data.title"></div>
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
                  this.html = response.data.val.short_description;
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
