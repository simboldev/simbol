<!-- <?php -->

namespace App;

use App\banco_pais_monedas;
use Illuminate\Database\Eloquent\Model;

class banco extends Model
{
    //
    // protected $primaryKey = 'idbancos';
    protected $fillable = [
    	'nombre',
    ]; 

	public function banco_pais_monedas()
	{
		return $this->belongsToMany(banco_pais_monedas::class);
	}
    
}
