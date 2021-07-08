<?
namespace App\Http\Middleware;

use Closure;
use App\Domain;
use Illuminate\Support\Facades\View;

class CustomDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        $domain = $request->getHost();
        $landing = Domain::where('title', $domain)->firstOrFail();

        $request->merge([
            'domain' => $domain,
            'landing' => $landing
        ]);

        return $next($request);
    }
}