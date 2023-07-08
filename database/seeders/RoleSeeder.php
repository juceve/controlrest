<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Programador']);
        $role1 = Role::create(['name' => 'Admin']);

        Permission::create(['name' => 'home', 'grupo' => 'HOME', 'descripcion' => 'Home'])->syncRoles([$role, $role1]);     

        Permission::create(['name' => 'conf.configuraciones', 'grupo' => 'CONFIGURACIONES', 'descripcion' => 'Configuraciones'])->syncRoles([$role]);  
        Permission::create(['name' => 'conf.parametros', 'grupo' => 'CONFIGURACIONES', 'descripcion' => 'Parametros'])->syncRoles([$role]);  

        Permission::create(['name' => 'ventas.index',  'grupo' => 'VENTAS', 'descripcion' => 'Ver listado'])->assignRole([$role]);
        // Permission::create(['name' => 'ventas.create',  'grupo' => 'VENTAS', 'descripcion' => 'Crear'])->assignRole([$role]);
        Permission::create(['name' => 'ventas.edit',  'grupo' => 'VENTAS', 'descripcion' => 'Editar'])->assignRole([$role]);
        Permission::create(['name' => 'ventas.destroy',  'grupo' => 'VENTAS', 'descripcion' => 'Anular'])->assignRole([$role]);               
        Permission::create(['name' => 'reservas.nueva','grupo' => 'VENTAS', 'descripcion' => 'Reservas'])->syncRoles([$role]);
        Permission::create(['name' => 'ventas.bonos','grupo' => 'VENTAS', 'descripcion' => 'Venta de Bonos'])->syncRoles([$role]);
        Permission::create(['name' => 'ventas.cierrecaja','grupo' => 'VENTAS', 'descripcion' => 'Cierres de Caja'])->syncRoles([$role]);
        Permission::create(['name' => 'ventas.anulacierre','grupo' => 'VENTAS', 'descripcion' => 'Anula Cierres'])->syncRoles([$role]);
        
        Permission::create(['name' => 'ventas.vpagos','grupo' => 'PAGOS', 'descripcion' => 'Listado Pagos pendientes'])->syncRoles([$role]);
        Permission::create(['name' => 'ventas.appedido','grupo' => 'PAGOS', 'descripcion' => 'Aprobar Pendientes'])->syncRoles([$role]);
        Permission::create(['name' => 'pagos.profesores','grupo' => 'PAGOS', 'descripcion' => 'Credito Profesores'])->syncRoles([$role]);
        
        
        Permission::create(['name' => 'ventas.pos','grupo' => 'PUNTO DE VENTA', 'descripcion' => 'Punto de Venta'])->syncRoles([$role]);
        Permission::create(['name' => 'ventasconfigs.index','grupo' => 'PUNTO DE VENTA', 'descripcion' => 'Listado Configs POS'])->syncRoles([$role]);
        Permission::create(['name' => 'ventasconfigs.create','grupo' => 'PUNTO DE VENTA', 'descripcion' => 'Nueva Configs POS'])->syncRoles([$role]);
        Permission::create(['name' => 'ventasconfigs.edit','grupo' => 'PUNTO DE VENTA', 'descripcion' => 'Editar Configs POS'])->syncRoles([$role]);

        Permission::create(['name' => 'entregas.individual','grupo' => 'ENTREGAS', 'descripcion' => 'Individuales'])->syncRoles([$role]);
        Permission::create(['name' => 'entregas.porcurso','grupo' => 'ENTREGAS', 'descripcion' => 'Por Curso'])->syncRoles([$role]);
        Permission::create(['name' => 'entregas.profesores','grupo' => 'ENTREGAS', 'descripcion' => 'Profesores'])->syncRoles([$role]);
        Permission::create(['name' => 'entregas.estadopedidos','grupo' => 'ENTREGAS', 'descripcion' => 'Estado Pedidos'])->syncRoles([$role]);

        Permission::create(['name' => 'tutores.index','grupo' => 'TUTORES', 'descripcion' => 'Ver listado'])->syncRoles([$role]);
        Permission::create(['name' => 'tutores.create','grupo' => 'TUTORES', 'descripcion' => 'Crear'])->syncRoles([$role]);
        Permission::create(['name' => 'tutores.edit','grupo' => 'TUTORES', 'descripcion' => 'Editar'])->syncRoles([$role]);
        Permission::create(['name' => 'tutores.destroy','grupo' => 'TUTORES', 'descripcion' => 'Eliminar'])->syncRoles([$role]);

        Permission::create(['name' => 'estudiantes.index','grupo' => 'ESTUDIANTES', 'descripcion' => 'Ver listado'])->syncRoles([$role]);
        Permission::create(['name' => 'estudiantes.create','grupo' => 'ESTUDIANTES', 'descripcion' => 'Crear'])->syncRoles([$role]);
        Permission::create(['name' => 'estudiantes.edit','grupo' => 'ESTUDIANTES', 'descripcion' => 'Editar'])->syncRoles([$role]);
        Permission::create(['name' => 'estudiantes.destroy','grupo' => 'ESTUDIANTES', 'descripcion' => 'Eliminar'])->syncRoles([$role]);

        Permission::create(['name' => 'cursos.index',  'grupo' => 'CURSOS', 'descripcion' => 'Ver listado'])->assignRole([$role]);
        Permission::create(['name' => 'cursos.create',  'grupo' => 'CURSOS', 'descripcion' => 'Crear'])->assignRole([$role]);
        Permission::create(['name' => 'cursos.edit',  'grupo' => 'CURSOS', 'descripcion' => 'Editar'])->assignRole([$role]);
        Permission::create(['name' => 'cursos.destroy',  'grupo' => 'CURSOS', 'descripcion' => 'Eliminar'])->assignRole([$role]);

        Permission::create(['name' => 'nivelcursos.index',  'grupo' => 'NIVEL CURSO', 'descripcion' => 'Ver listado'])->assignRole([$role]);
        Permission::create(['name' => 'nivelcursos.create',  'grupo' => 'NIVEL CURSO', 'descripcion' => 'Crear'])->assignRole([$role]);
        Permission::create(['name' => 'nivelcursos.edit',  'grupo' => 'NIVEL CURSO', 'descripcion' => 'Editar'])->assignRole([$role]);
        Permission::create(['name' => 'nivelcursos.destroy',  'grupo' => 'NIVEL CURSO', 'descripcion' => 'Eliminar'])->assignRole([$role]);

        Permission::create(['name' => 'items.index','grupo' => 'PRODUCTOS', 'descripcion' => 'Ver listado'])->syncRoles([$role]);
        Permission::create(['name' => 'items.create','grupo' => 'PRODUCTOS', 'descripcion' => 'Crear'])->syncRoles([$role]);
        Permission::create(['name' => 'items.edit','grupo' => 'PRODUCTOS', 'descripcion' => 'Editar'])->syncRoles([$role]);
        Permission::create(['name' => 'items.destroy','grupo' => 'PRODUCTOS', 'descripcion' => 'Eliminar'])->syncRoles([$role]);
        
        Permission::create(['name' => 'catitems.index','grupo' => 'CAT. PRODUCTOS', 'descripcion' => 'Ver listado'])->syncRoles([$role]);
        Permission::create(['name' => 'catitems.create','grupo' => 'CAT. PRODUCTOS', 'descripcion' => 'Crear'])->syncRoles([$role]);
        Permission::create(['name' => 'catitems.edit','grupo' => 'CAT. PRODUCTOS', 'descripcion' => 'Editar'])->syncRoles([$role]);
        Permission::create(['name' => 'catitems.destroy','grupo' => 'CAT. PRODUCTOS', 'descripcion' => 'Eliminar'])->syncRoles([$role]);

        Permission::create(['name' => 'menus.index','grupo' => 'MENU', 'descripcion' => 'Ver listado'])->syncRoles([$role]);
        Permission::create(['name' => 'menus.create','grupo' => 'MENU', 'descripcion' => 'Crear'])->syncRoles([$role]);
        Permission::create(['name' => 'menus.edit','grupo' => 'MENU', 'descripcion' => 'Editar'])->syncRoles([$role]);
        Permission::create(['name' => 'menus.destroy','grupo' => 'MENU', 'descripcion' => 'Eliminar'])->syncRoles([$role]);
        Permission::create(['name' => 'menus.eventos','grupo' => 'MENU', 'descripcion' => 'Programa Semanal'])->syncRoles([$role]);    
        
        Permission::create(['name' => 'precios.index','grupo' => 'PRECIO MENU', 'descripcion' => 'Ver listado'])->syncRoles([$role]);
        Permission::create(['name' => 'precios.create','grupo' => 'PRECIO MENU', 'descripcion' => 'Crear'])->syncRoles([$role]);
        Permission::create(['name' => 'precios.edit','grupo' => 'PRECIO MENU', 'descripcion' => 'Editar'])->syncRoles([$role]);
        Permission::create(['name' => 'precios.destroy','grupo' => 'PRECIO MENU', 'descripcion' => 'Eliminar'])->syncRoles([$role]);

        Permission::create(['name' => 'feriados.index','grupo' => 'FERIADOS', 'descripcion' => 'Ver listado'])->syncRoles([$role]);
        Permission::create(['name' => 'feriados.create','grupo' => 'FERIADOS', 'descripcion' => 'Crear'])->syncRoles([$role]);
        Permission::create(['name' => 'feriados.edit','grupo' => 'FERIADOS', 'descripcion' => 'Editar'])->syncRoles([$role]);
        Permission::create(['name' => 'feriados.destroy','grupo' => 'FERIADOS', 'descripcion' => 'Eliminar'])->syncRoles([$role]);
         
        Permission::create(['name' => 'monedas.index','grupo' => 'MONEDA', 'descripcion' => 'Ver listado'])->syncRoles([$role]);
        Permission::create(['name' => 'monedas.create','grupo' => 'MONEDA', 'descripcion' => 'Crear'])->syncRoles([$role]);
        Permission::create(['name' => 'monedas.edit','grupo' => 'MONEDA', 'descripcion' => 'Editar'])->syncRoles([$role]);
        Permission::create(['name' => 'monedas.destroy','grupo' => 'MONEDA', 'descripcion' => 'Eliminar'])->syncRoles([$role]);

        Permission::create(['name' => 'admin.empresas.index',  'grupo' => 'EMPRESAS', 'descripcion' => 'Ver listado'])->assignRole([$role]);
        Permission::create(['name' => 'admin.empresas.create',  'grupo' => 'EMPRESAS', 'descripcion' => 'Crear'])->assignRole([$role]);
        Permission::create(['name' => 'admin.empresas.edit',  'grupo' => 'EMPRESAS', 'descripcion' => 'Editar'])->assignRole([$role]);
        Permission::create(['name' => 'admin.empresas.destroy',  'grupo' => 'EMPRESAS', 'descripcion' => 'Eliminar'])->assignRole([$role]);

        Permission::create(['name' => 'admin.sucursales.index',  'grupo' => 'SUCURSALES', 'descripcion' => 'Ver listado'])->assignRole([$role]);
        Permission::create(['name' => 'admin.sucursales.create',  'grupo' => 'SUCURSALES', 'descripcion' => 'Crear'])->assignRole([$role]);
        Permission::create(['name' => 'admin.sucursales.edit',  'grupo' => 'SUCURSALES', 'descripcion' => 'Editar'])->assignRole([$role]);
        Permission::create(['name' => 'admin.sucursales.destroy',  'grupo' => 'SUCURSALES', 'descripcion' => 'Eliminar'])->assignRole([$role]);

        Permission::create(['name' => 'admin.users.index',  'grupo' => 'USUARIOS', 'descripcion' => 'Ver listado'])->assignRole([$role]);
        Permission::create(['name' => 'admin.users.create',  'grupo' => 'USUARIOS', 'descripcion' => 'Crear'])->assignRole([$role]);
        Permission::create(['name' => 'admin.users.edit',  'grupo' => 'USUARIOS', 'descripcion' => 'Editar'])->assignRole([$role]);

        Permission::create(['name' => 'admin.roles.index',  'grupo' => 'ROLES', 'descripcion' => 'Ver listado'])->assignRole([$role]);
        Permission::create(['name' => 'admin.roles.create',  'grupo' => 'ROLES', 'descripcion' => 'Crear'])->assignRole([$role]);
        Permission::create(['name' => 'admin.roles.edit',  'grupo' => 'ROLES', 'descripcion' => 'Editar'])->assignRole([$role]);
        Permission::create(['name' => 'admin.roles.destroy',  'grupo' => 'ROLES', 'descripcion' => 'Eliminar'])->assignRole([$role]);

    }
}
