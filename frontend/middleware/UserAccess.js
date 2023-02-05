export default async function({store, redirect, next, $auth, $toast}){
  let user = $auth.user;
  if(user){

  }else{
    if(process.client){
      $toast.success('Bu sayfaya erişim engellendi.');
    }
    redirect('/login');
  }
}
