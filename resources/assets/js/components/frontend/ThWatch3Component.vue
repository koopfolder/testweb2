<template>
  <fragment>
    <section class="row wow fadeInDown registeronline" ref="homeCont">
        <img v-bind:src="background" v-bind:alt="text_h1">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <h1>{{ text_h1 }}</h1>
                    <p>{{ title }}</p>
                    <a v-bind:href="url_button">{{ text_button }}</a>
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
                  fullPage: false,
                  title:this.text_p,
                  text_button:this.text_register,
                  url_button:this.url_register
                 } 
        },
        props: {
          api_url:{},
          background:{},
          url_register:{},
          access_token:{},
          text_register:{},
          text_form_generator:{},
          text_h1:{},
          text_p:{}
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
                  //this.data = response.data.val;
                  if(response.data.val !=''){
                    this.url_button = response.data.val.url;
                    this.title =response.data.val.title;
                    this.text_button = this.text_form_generator;
                  }

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
