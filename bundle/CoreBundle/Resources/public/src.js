var hepler=
  function create(value)
  {
    value.template='<b>'+value.template+'</b>';
    return value;
  };


export default  hepler({
    name: 'helloworld',
    props: {},
    template:  'MyTemplateUrl'

  });