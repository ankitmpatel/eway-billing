const { constant } = require("lodash");
import axios from 'axios';

export const fetchRoutes = (setRoutes) => {
    return axios.post(api_endpoint_routing_jobs, {
    })
    .then(function (response) {
        let newRoutes = response.data;
        setRoutes(newRoutes)

    })
    .catch(function (error) {
        console.log(error);
    });
}

export const fetchEwayBill = (setEwayBill, data = {params: {
    ID: 12345
  }}) => {
    return axios.get(api_endpoint_ewaybill, data)
    .then(function (response) {
        // let newDeliveries = response.data;
        setEwayBill(newDeliveries)
    })
    .catch(function (error) {
        console.log(error);
    });
}

export const fetchDeliveryExceptions = (setDeliveryExceptions, data = {}) => {
    return axios.post(api_endpoint_delivery_exception, data)
        .then(function (response) {
            let newDeliveryException = response.data;
            setDeliveryExceptions(newDeliveryException)
        })
        .catch(function (error) {
            console.log(error);
        });
}


export const fetchAccounts = (setAccounts) => {
    return axios.post(api_endpoint_accounts, {})
    .then(function(response) {
        setAccounts(response.data);
    })
    .catch(function(error) {
        console.log('error: ', error);
    });
}

export const fetchDrivers = (setDrivers) => {
    return axios.post(api_endpoint_drivers, {})
    .then(function(response) {
        setDrivers(response.data);
    })
    .catch(function(error) {
        console.log('error: ', error);
    });
}

export const fetchAvailableStatuses = (setAvailableStatuses, data = {}) => {
    return axios.post(api_endpoint_statuses, data)
    .then(function (response) {
        let newDeliveries = response.data;
        setAvailableStatuses(newDeliveries)
    })
    .catch(function (error) {
        console.log(error);
    });
};

export const fetchResponseTypes = (setResponseTypes) => {
    return axios.post(api_endpoint_reponse_types, {})
        .then(function (response) {
            setResponseTypes(response.data);
        })
        .catch(function (error) {
            console.log('error: ', error);
        });
};

export const fetchResolutionResponses= (setResolutionResponses,data = {}) => {
    axios.post(get_response, data)
        .then(function (response) {
            setResolutionResponses(response.data);
        })
        .catch(function (error) {
            console.log('error: ', error);
        });
};

export const fetchDriverLocations = (addDriverLocations) => {
    axios.post(api_endpoint_driver_locations)
        .then(function(response) {
            addDriverLocations(response.data);
        })
        .catch(function(error) {
            console.log('error: ', error);
        });
};

export const fetchDriverExceptions = (setDriverExceptions, data = {}) => {
    return axios.post(api_endpoint_driver_exceptions, data)
        .then(function (response) {
            let newDriverException = response.data;
            setDriverExceptions(newDriverException)
        })
        .catch(function (error) {
            console.log(error);
        });
}
