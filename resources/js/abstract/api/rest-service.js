import axios from 'axios';

class RestService {
  /**
   * Handles external resources fetch
   *
   * @param options
   * @return {Promise<AxiosResponse<any>>}
   */
  fetch(options) {
    return axios({
      baseURL: '/',
      ...options
    }).then(response => response.data);
  }
}

export default new RestService();
