<template>
  <fragment>  
    <div ref="homeCont">
        <section class="row wow fadeInDown" >
            <div class="col-xs-12 banner-inside">
                <img v-bind:src="panel_discussion_cover_image" v-bind:alt="panel_discussion_title">
            </div>
        </section>
        <section class="row wow fadeInDown">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 head_inside">
                        <h1>{{ panel_discussion_title }}</h1>
                        <div v-html="panel_discussion_description"></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="row wow fadeInDown">
            <div class="container" v-for="(index) of years">
                <div class="row">
                    <div class="col-xs-12 head_year">
                        <h3>{{ text_year }} {{ index }}</h3>
                    </div>
                </div>
                <div class="row row_trend">
                    <div class="col-xs-12 col-sm-6 col-md-4 item_trend item_speaker" v-for="(data,i) in panel_discussion[index]">
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
                <div class="row">
                    <div class="col-xs-12">
                        <button class="btn_loadmore" v-if="panel_discussion_all[index].panel_discussion_lastPage >1" v-on:click="read_more(index)">{{ text_read_more }}</button>
                    </div>
                </div>            
            </div>
        </section>
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
                  panel_discussion_title:'',
                  panel_discussion_description:'',
                  panel_discussion_cover_image:'',
                  panel_discussion:[],
                  panel_discussion_all:[],
                  years:[],
                  fullPage:true
                 } 
        },
        props: {
          text_year:{},
          api_url:{},
          api_url_read_more:{},
          access_token:{},
          text_read_more:{},
        },
        methods: {
          read_more(key){
              //console.log(key);
            
              let total;
              let lastPage;
              let perPage;
              let nextPage;

              let loader = this.$loading.show({
              container: this.fullPage ? null : this.$refs.homeCont,
              canCancel: false,
              loader:'dots',
              color:'#ef7e25'
              });



                total = this.panel_discussion_all[key].panel_discussion_total;
                lastPage = this.panel_discussion_all[key].panel_discussion_lastPage;
                perPage =  this.panel_discussion_all[key].panel_discussion_perPage;
                this.panel_discussion_all[key].panel_discussion_currentPage = this.panel_discussion_all[key].panel_discussion_currentPage+1;
                nextPage = this.panel_discussion_all[key].panel_discussion_currentPage; 
                let year = key;            
            
                if(nextPage >= lastPage){
                    this.panel_discussion_all[key].panel_discussion_lastPage  = 1
                }


                var postData = {
                    page:nextPage,
                    year:year
                    //test:window.btoa('2008,2009')
                };
              

                 if(nextPage <=lastPage){

                //console.log("True");

                  let axiosConfig = {
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8',
                        'authorization': 'Bearer '+this.access_token
                        //"x-xsrf-token":$('meta[name="csrf-token"]').attr('content'),
                    }
                  };


                  axios.post(this.api_url_read_more,postData,axiosConfig)
                    .then(response=>{
                            // handle success
                        if(response.status ==200){
                        loader.hide();      
                        //console.log(response.data);
                        for(let key_data in response.data.val.panel_discussion){
                            //console.log();
                            this.panel_discussion[year].push(response.data.val.panel_discussion[key_data]); 
                            //this.article.push(response.data.tab[key_data]); 
                        }
                        
                        setTimeout(function(){  

                        }, 2000);

                        }
                      })

                    .catch(function (error){
                      // handle error
                      console.log(error);
                    })
                    .then(function(){
                      // always executed
                  });
                }
              //loader.hide();
              //console.log(tag_name,total,lastPage,perPage,nextPage);
          }
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

                  //this.panel_discussion = response.data.val.panel_discussion;
                  this.panel_discussion_title = response.data.val.panel_discussion_title;
                  this.panel_discussion_description = response.data.val.panel_discussion_description;
                  this.panel_discussion_cover_image = response.data.val.panel_discussion_cover_image;
                  this.panel_discussion_all = response.data.val.panel_discussion;

                  //this.years = [2021,2020];
                  for (let index in response.data.val.panel_discussion) {
                       this.years.push(index);
                       this.panel_discussion[index] = response.data.val.panel_discussion[index]['data'];
                  }
                  this.years.sort().reverse();
           
                  //console.log(this.panel_discussion);
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

          }
        }
    }

</script>
