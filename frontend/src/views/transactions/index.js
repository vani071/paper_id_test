import React, { useState, useEffect } from 'react'
import { useHistory, useLocation } from 'react-router-dom'
import {
  CCard,
  CCardBody,
  CCardHeader,
  CCol,
  CRow,
} from '@coreui/react'

import qs from 'qs';
import {customAxios} from '../../services/customAxios';

import BootstrapTable from 'react-bootstrap-table-next';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'react-bootstrap-table-next/dist/react-bootstrap-table2.css';
import 'react-bootstrap-table2-paginator/dist/react-bootstrap-table2-paginator.min.css';
import paginationFactory from 'react-bootstrap-table2-paginator';

const data = [
  {
      id: 3,
      trx_name: "pembelian mahar",
      created_by: "vani lah",
      company_name: null,
      trx_type: "expense",
      trx_amount: "200000.00",
      trx_status: "pending"
  },
  {
      id: 2,
      trx_name: "pembelian mahar",
      created_by: "vani lah",
      company_name: "PT. Ala kadarnya",
      trx_type: "expense",
      trx_amount: "200000.00",
      trx_status: "pending"
  },
  {
      id: 1,
      trx_name: "pembelian mahar",
      created_by: "vani lah",
      company_name: "PT. Ala kadarnya",
      trx_type: "expense",
      trx_amount: "200000.00",
      trx_status: "pending"
  }
];

const columns = [
  { dataField: 'id', text: 'Id', sort: true},
  { dataField: 'trx_name', text: 'Trx name', sort: true},
  { dataField: 'company_name', text: 'Company Name', sort: true},
  { dataField: 'created_by', text: 'Created By', sort: true},
  { dataField: 'trx_type', text: 'Type', sort: true},
  { dataField: 'trx_amount', text: 'Amount', sort: true},
  { dataField: 'trx_status', text: 'Status', sort: true}
];

const sordDefaultValue = {
    dataField: 'id',
    order: 'desc'
  };
const defaultSorted = [sordDefaultValue];




const Users = () => {
  const [sortField,setSortField] = useState(sordDefaultValue.dataField);
  const [sortType,setSortType] = useState(sordDefaultValue.order);
  const [page,setPage] = useState(1);
  const [keyword,setKeyword] = useState('');
  const [status,setStatus] = useState('');
  const [type,setType] = useState('');
  const [pageSize,setPageSize] = useState(10);
  const [dataTable,setDataTable] = useState([]);
  const [reload,setReload] = useState(true);
  const [total,setTotal] = useState(0);
  const [pageTotal,setPageTotal] = useState(1);

  const pagination = paginationFactory({
    page: page,
    paginationSize: pageSize,
    lastPageText: '>>',
    firstPageText: '<<',
    nextPageText: '>',
    prePageText: '<',
    showTotal: true,
    totalSize:total,
    alwaysShowAllBtns: true,
    onPageChange: function (page, sizePerPage) {
        setPage(page);
        setPageSize(sizePerPage);
        setReload(true);
        console.log('page 1', page);
        console.log('sizePerPage 1', sizePerPage);
    },
    onSizePerPageChange: function (page, sizePerPage) {
        console.log('page', page);
        console.log('sizePerPage', sizePerPage);
        // setPage(sizePerPage);
        setPageSize(page);
        setReload(true);
    }
  });

  const GetDataTable = ()=>{
    const dataForm = {
        sortField:sortField,
        sortType:sortType,
        page:page,
        keyword:keyword,
        status:status,
        type:type,
        pageSize:pageSize
      }
      customAxios({
        url:'/transactions',
        method:"GET",
        params:dataForm
        // data:qs.stringify(dataForm)
      }).then(response=>{
        if(response.status === 200){
          setTotal(response.data.total)
          setPageTotal(response.data.last_page)
          setDataTable(response.data.data)
          setReload(false);
        }
      }).catch(error=>{
        console.log(error.response)
      })
  }

  useEffect(() => {
    if(reload){
        GetDataTable();
        setReload(false);
    } 
  }, [reload])

  const onTableChange = (type, newState) => {
    // handle any data change here
    console.log(type);
     switch (type) {
       case 'pagination':
        setPage(newState.page);         
         break;

      case 'sort':
        setSortField(newState.sortField);     
        setSortType(newState.sortOrder);     
        break;

       default:
         break;
     }

      setReload(true);
  }
  

  return (
    <CRow>
      <CCol xl={12}>
        <CCard>
          <CCardHeader>
            Users
            <small className="text-muted"> example</small>
          </CCardHeader>
          <CCardBody>
              <BootstrapTable bootstrap4 keyField='id' 
                 data={dataTable} 
                 columns={columns} 
                 defaultSorted={defaultSorted} 
                 pagination={pagination}
                 remote={ { pagination: true, filter: false, sort: true } }
                 onTableChange={onTableChange}
                 />
          </CCardBody>
        </CCard>
      </CCol>
    </CRow>
  )
}

export default Users
