<?php
class ControllerShoptifyShoptifyapp extends Controller {
    
        public function index(){
            $this->load->model('shoptify/shoptify');
            $accesstoken = $this->model_shoptify_shoptify->getShoptifyAccessToken() ;
            $token = json_decode($accesstoken);
            $data['aaaa']='a';
            if($token != null && isset($token->access_token)){
                header("Location:". $this->config->get('config_url'));
            }
            else {
                $this->load->config('shoptify_app');
                $redirect_url = $this->config->get('config_url')."index.php?route=shoptify/shoptify_app/callback"; 
                $data['hmac'] = $this->request->get['hmac'];
                $data['shoptify_shop'] = $this->request->get['shop'];
                $data['timestamp'] = $this->request->get['timestamp'];
                $data['shoptify_api_key'] =$this->config->get('shoptify_api_key');
                $data['shoptify_api_secret_key'] =$this->config->get('shoptify_api_secret_key');

                $data['outh_url'] ="https://"
                        . $data['shoptify_shop']
                        . "/admin/oauth/authorize?client_id="
                        .  $data['shoptify_api_key']
                        . "&scope="
                        . "write_orders,read_customers"
                        . "&redirect_uri="
                        . $redirect_url
                        . "&state="
                        . "temp_state_code"//generate state here 
                        . "&grant_options[]="
                        . " ";

                header("Location:". $data['outh_url']);
            }    
        }
	
        
        public function callback(){
            
            $this->load->config('shoptify_app');
            $data["state"]  = $this->request->get['state'];
            if(isset($data['state'])&&$data["state"] =="temp_state_code"){
                
                $this->load->model('shoptify/shoptify');
                
                $data["code"]  = $this->request->get['code'];
                $data['shop']   = $this->request->get['shop'];
                $req['client_id'] =$this->config->get('shoptify_api_key');
                $req['client_secret'] =$this->config->get('shoptify_api_secret_key');
                $req['code'] =    $data["code"];

                $url = "https://".$data['shop']."/admin/oauth/access_token";
                
                $this->load->model('tool/shoptify');
                $feedback = ($this->model_tool_shoptify->httpSendPost($url,$req));
                $this->model_shoptify_shoptify->setShoptifyAccessToken($feedback);
                
            }//generate state here 
            else{
                $this->response->setOutput($this->load->view('error/not_found'));
                echo "not valid";
            }
           
            
            echo "<br>the access token is :<br>"; 
        }
        
        public function test(){
            $this->load->model('shoptify/shoptify');
            $accesstoken = $this->model_shoptify_shoptify->getShoptifyAccessToken() ; 
            $token = json_decode($accesstoken); 
            print_r($token);
        }
        
        
        public function example() {
            $this->response->setOutput($this->load->view('error/not_found', $data));
                
	}
}