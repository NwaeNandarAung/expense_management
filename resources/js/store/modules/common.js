const headers = {
    Accept: "application/json",
    Authorization: 'Bearer ' + localStorage.getItem('token'),
    withCredentials: true,
}

export default headers
