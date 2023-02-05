export default async function({store, redirect, next, $auth, $toast}){
  let user = $auth.user;
  if(user && user.isAdmin){

  }else{
    if(process.client){
      $toast.success('Bu sayfaya erişim engellendi.');
    }
    redirect('/login');
  }
}
