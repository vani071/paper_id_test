import axios from 'axios';


const { REACT_APP_API_URL } = process.env;

const customAxios = axios.create({
    baseURL: REACT_APP_API_URL
  });

customAxios.interceptors.request.use( config => {
  // check is request for non authentication
  console.log('url interceptor',config.url);
  const token = localStorage.getItem("token");
  if (config.url !== 'login') {
    if(token === undefined || token === null) {
      localStorage.clear();
      window.location.href = '/#/login';
    }
    config.headers = {'Authorization':`Bearer ${token}`}
  }

  if(config.method.toLowerCase() === 'post' || config.method.toLowerCase() === 'put'){
    config.headers = {
      'Authorization':`Bearer ${token}`,
      'Content-Type':'application/x-www-form-urlencoded;charset=utf-8'
    };
  }
  return config;
});

customAxios.interceptors.response.use(res => {
  return res;
},(error)=>{
  if(error.response.status === 401 || error.response.status === 405){
    localStorage.clear();
    window.location.href = '/#/login';
  }
  return Promise.reject(error);
});


export { customAxios };