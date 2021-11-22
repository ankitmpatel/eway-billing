import React from 'react';
import ListContext from './ListContext'
import {fetchEwayBill} from '../../common/FetchData'

function Page() {
  const [ewayBill, setEwayBill] = React.useState([]);

  React.useEffect(function () {
    fetchEwayBill(setEwayBill,{id:"23"});
  }, []);


  return <ListContext.Provider value={{
    ewayBill: ewayBill,
    setEwayBill: setEwayBill,
  }}>
    <h1>Hello</h1>
  </ListContext.Provider>
}

export default Page;
